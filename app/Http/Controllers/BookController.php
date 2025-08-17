<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function show($book)
    {
        $book = Book::find($book);

        if (!$book) {
            return response()->json([
                'message' => 'Book not found.'
            ], 404);
        }

        return new BookResource($book);
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
