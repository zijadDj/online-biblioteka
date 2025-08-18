<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
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
    public function store(BookRequest $request)
    {
        $params = $request->validated();

        DB::beginTransaction();

        try {
            $book = Book::create($params);

            if ($request->hasFile('cover_image')) {
                $file = $request->file('cover_image');
                $filepath = $file->store('book_covers', 'public');

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }


}
