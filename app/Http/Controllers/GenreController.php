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
        $genre = Genre::create($params);
        return new GenreResource($genre);
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genre $genre)
    {
        $params = $request->validated();
        $genre->update($params);
        $genre->load('books');
        return new GenreResource($genre);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre)
    {
        $genre->delete();
        return response()->json(['message' => 'Genre Deleted Successfully']);
    }
}
