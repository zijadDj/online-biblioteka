<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $params = $request->validated();

        DB::beginTransaction();

        try {
            $book = Book::create($params);

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $filepath = $file->store('covers', 'public');

                Image::create([
                    'path' => $filepath,
                    'book_id' => $book->id
                ]);
            }

            DB::commit();
            $book->load('image');
            return new BookResource($book);

        } catch (\Exception $e) {
            DB::rollback();

            if (isset($filepath) && Storage::disk('public')->exists($filepath)) {
                Storage::disk('public')->delete($filepath);
            }

            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book = Book::find($book);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found.'
            ], 404);
        }

        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function cover($bookId)
    {
        $image = Image::where('book_id', $bookId)
            ->where('type', 'cover')
            ->first();

        if (!$image) {
            return response()->json([
                'message' => 'Cover not found.'
            ], 404);
        }

        $coverPath = 'covers/' . ltrim($image->path, '/');

        if (!Storage::disk('public')->exists($coverPath)) {
            return response()->json([
                'message' => 'Cover file does not exist on server.'
            ], 404);
        }

        // Return the file
        return response()->file(storage_path("app/public/{$coverPath}"));
    }
}
