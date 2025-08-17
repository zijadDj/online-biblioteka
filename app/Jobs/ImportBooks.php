<?php

namespace App\Jobs;

use App\Models\Book;
use App\Models\User;
use App\Notifications\ImportSuccessful;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportBooks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $apiEndpoint;
    protected  int $userId;

    /**
     * Create a new job instance.
     */
    public function __construct($userId)
    {
        $this->apiEndpoint = 'https://www.googleapis.com/books/v1/volumes?q=api';
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $response = Http::retry(3,100)->withHeaders([
                'Accept' => 'application/json',
            ])->get($this->apiEndpoint);

            if ($response->successful()) {
                $books = $response->json();
                $this->saveData($books);
                $user = User::findOrFail($this->userId);
                $user->notify(new ImportSuccessful());
            }
        } catch (\Exception $e) {
            Log::error('Job failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function saveData($data)
    {
        foreach ($data['items'] as $book) {
            if (!isset($book['id']) || !isset($book['volumeInfo'])) {
                continue;
            }

            $volumeInfo = $book['volumeInfo'];

            Book::updateOrCreate(
                ['google_id' => $book['id']],
                [
                    'google_id' => $book['id'],
                    'name' => $volumeInfo['title'],
                    'publisher' => $volumeInfo['publisher'],
                    'description' => $volumeInfo['description'],
                    'language' => $volumeInfo['language'] ,
                    'page_count' => $volumeInfo['pageCount'] ,
                    'binding' => 'paperback',
                    'script' => 'latin',
                    'dimensions' => 'A1',
                    'unit_count' => 50,
                    'isbn' => $this->getIsbn($volumeInfo),
                ]
            );
        }
    }
    private function getIsbn($book)
    {
        if (!isset($book['industryIdentifiers']) || !is_array($book['industryIdentifiers'])) {
            return 'No ISBN found';
        }

        foreach ($book['industryIdentifiers'] as $industryIdentifier) {
            if (isset($industryIdentifier['type']) && $industryIdentifier['type'] === 'ISBN_13') {
                return $industryIdentifier['identifier'] ?? null;
            }
        }
        return 'No ISBN found';
    }
}
