<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreRequest;
use App\Http\Resources\GenreResource;
use App\Models\Genre;
use Illuminate\Http\Request;




class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchTerm = request()->input('q');
        $query = Genre::query();
        if ($searchTerm) {
            $query->where('name', 'like', '%' . trim($searchTerm) . '%');
        }
        return GenreResource::collection($query->paginate(10)) ;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GenreRequest $request)
    {
        $params = $request->validated();
        $attachBookIds = $params['book_ids'] ?? [];
        unset($params['book_ids']);
        $genre = Genre::create($params);

        if ($request->has('book_ids')) {
            $genre->books()->attach($attachBookIds);
    }
        $genre->load('books');
        return new GenreResource ($genre);

    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre)
    {
        $genre->load('books');
        return new GenreResource($genre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GenreRequest $request, Genre $genre)
    {
        $params = $request->validated();
        $attachBookIds = $params['book_ids'] ?? [];
        $detachBookIds = $params['remove_book_ids'] ?? [];
        unset($params['book_ids']);
        unset($params['remove_book_ids']);
        $genre->update($params);

        if ($request->has('book_ids')) {
            $genre->books()->syncWithoutDetaching($attachBookIds);
    }
        if ($request->has('remove_book_ids')) {
            $genre->books()->detach($detachBookIds);
        }
        $genre->load('books');
        return new GenreResource($genre);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        //
    }
}
