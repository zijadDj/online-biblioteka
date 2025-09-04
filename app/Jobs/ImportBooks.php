<?php

    namespace App\Jobs;

    use App\Models\Author;
    use App\Models\Book;
    use App\Models\Category;
    use App\Models\Publisher;
    use App\Models\User;
    use App\Notifications\ImportSuccessful;
    use Exception;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Queue\Queueable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Support\Facades\Http;
    use Illuminate\Support\Facades\Log;

    class ImportBooks implements ShouldQueue
    {
        use Queueable, SerializesModels;

        /**
         * Create a new job instance.
         */
        public function __construct(public User $user)
        {

        }

        /**
         * Execute the job.
         */
        public function handle(): void
        {
            try {
                $response = Http::timeout(30)->get('https://www.googleapis.com/books/v1/volumes?q=api');

                if (!$response->successful()) {
                    Log::error('Failed to fetch books from Google Books API', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return;
                }

                $data = $response->json();

                if (!isset($data['items']) || empty($data['items'])) {
                    Log::info('No books found in API response');
                    return;
                }

                foreach ($data['items'] as $item) {
                    $this->processBookItem($item);
                }
                $this->user->notify(new ImportSuccessful());
                Log::info('Books imported successfully', ['count' => count($data['items'])]);

            } catch (Exception $e) {
                Log::error('Error importing books', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        }

        private function processBookItem(array $item): void
        {
            if (!isset($item['id']) || !isset($item['volumeInfo'])) {
                return;
            }

            $volume = $item['volumeInfo'];

            $book = Book::updateOrCreate([
                'google_id' => $item['id'],
            ], [
                'name' => $volume['title'] ?? 'Unknown Title',
                'description' => $volume['description'] ?? null,
                'page_count' => $volume['pageCount'] ?? null,
                'language' => $volume['language'] ?? null,
                'isbn' => $this->resolveIsbn($volume['industryIdentifiers'] ?? []),
                'publisher_id' => $this->resolvePublisher($volume['publisher'] ?? null),
                'unit_count' => 100,
                'script' => 'latin',
                'binding' => 'paperback'
            ]);

            $this->syncBookRelations($book, $volume);
        }

        private function syncBookRelations(Book $book, array $volume): void
        {
            $authorIds = $this->resolveAuthors($volume['authors'] ?? []);
            if (!empty($authorIds)) {
                $book->authors()->sync($authorIds);
            }

            $categoryIds = $this->resolveCategories($volume['categories'] ?? []);
            if (!empty($categoryIds)) {
                $book->categories()->sync($categoryIds);
            }
        }

        private function resolveAuthors(array $authors): array
        {
            if (empty($authors)) {
                return [];
            }

            $authorIds = [];

            foreach ($authors as $author) {
                if (empty(($author))) {
                    continue;
                }

                $parts = explode(' ', ($author), 2);
                $firstName = $parts[0] ?? null;
                $lastName = $parts[1] ?? null;

                $dbAuthor = Author::firstOrCreate([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ], [
                    'biography' => ($firstName . ' ' . $lastName) . ' biography',
                    'picture' => 'default.png'
                ]);

                $authorIds[] = $dbAuthor->id;
            }

            return $authorIds;
        }

        private function resolveCategories(array $categories): array
        {
            if (empty($categories)) {
                return [];
            }

            $categoryIds = [];

            foreach ($categories as $category) {
                if (empty(($category))) {
                    continue;
                }

                $dbCategory = Category::firstOrCreate([
                    'name' => ($category),
                ], [
                    'description' => ($category) . ' description',
                    'icon' => 'default_icon.png'
                ]);

                $categoryIds[] = $dbCategory->id;
            }

            return $categoryIds;
        }

        private function resolveIsbn(array $identifiers): ?string
        {
            foreach ($identifiers as $identifier) {
                if (isset($identifier['type'], $identifier['identifier']) &&
                    $identifier['type'] === 'ISBN_13') {
                    return $identifier['identifier'];
                }
            }
            return null;
        }

        private function resolvePublisher(?string $publisher): ?int
        {
            if (empty($publisher)) {
                return null;
            }

            $publisherName = ($publisher);

            $dbPublisher = Publisher::firstOrCreate([
                'name' => $publisherName,
            ], [
                'address' => '460 Findley Avenue',
                'website' => 'www.' . strtolower(str_replace(' ', '', $publisherName)) . '.com',
                'email' => strtolower(str_replace(' ', '', $publisherName)) . '@gmail.com',
                'phone_number' => '+14054554003',
                'established_year' => date('Y'),
            ]);

            return $dbPublisher->id;
        }
    }
