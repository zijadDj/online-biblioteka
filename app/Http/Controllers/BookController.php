<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Requests\FilterBookRequest;
use App\Http\Resources\BookResource;
use App\Jobs\ImportBooks;
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
    public function index(FilterBookRequest $request)
    {
        $perPage = $request->input('per_page', 20);
        $search = $request->input('search-value');

        $query = Book::query();

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $books = $query->paginate($perPage);

        return BookResource::collection($books);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
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

            if ($request->has('genre_ids')) {
                $attachGenreIds = $request->input('genre_ids');
                unset($params['genre_ids']);
                $book->genres()->syncWithoutDetaching($attachGenreIds);
            }

            if ($request->has('remove_genre_ids')) {
                $removeGenreIds = $request->input('remove_genre_ids');
                unset($params['remove_genre_ids']);
                $book->genres()->detach($removeGenreIds);
            }

            DB::commit();
            $book->load('images', 'genres');
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
        $book->load('images', 'genres');
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        $params = $request->validated();
        if ($request->has('genre_ids')) {
            $attachGenreIds = $request->input('genre_ids');
            unset($params['genre_ids']);
            $book->genres()->syncWithoutDetaching($attachGenreIds);
        }
        if ($request->has('remove_genre_ids')) {
            $removeGenreIds = $request->input('remove_genre_ids');
            unset($params['remove_genre_ids']);
            $book->genres()->detach($removeGenreIds);
        }
        $book->update($params);
        $book->load('images', 'genres');
        return new BookResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        foreach ($book->images as $image) {
            $coverPath = 'covers/' . ltrim($image->path, '/');

            if (Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }

            $image->delete();
        }

        $book->delete();

        return response()->json([
            'message' => 'Book and associated images deleted successfully.'
        ], 200);
    }

    public function showCover(Book $book)
    {
        $image = $book->images()->where('type', 'cover')->first();

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

        return response()->file(storage_path("app/public/{$coverPath}"));
    }

    public function updateCover(Request $request, Book $book)
    {
        $request->validate([
            'cover_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ]);

        if ($request->hasFile('cover_image')) {
            $oldCoverImage = $book->images()->where('type', 'cover')->first();
            if ($oldCoverImage) {
                Storage::disk('public')->delete($oldCoverImage->path);
                $oldCoverImage->delete();
            }
            $file = $request->file('cover_image');
            $filepath = $file->store('covers', 'public');

            Image::create([
                'path' => $filepath,
                'book_id' => $book->id
            ]);

            return response()->json([
                'message' => 'Cover image updated successfully',
                'path' => $filepath
            ]);
        }

        return response()->json([
            'message' => 'No image file provided'
        ], 400);
    }

    public function import()
    {
        ImportBooks::dispatch(auth()->user());
        return response()->json([
            'message' => 'Books import job initialised successfully!'
        ]);
    }
}
