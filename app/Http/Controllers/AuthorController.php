<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\AuthorRequest;
    use App\Http\Resources\AuthorResource;
    use App\Models\Author;
    use Illuminate\Http\Request;

    class AuthorController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index(Request $request)
        {
            $limit = $request->query('limit', 20);
            $allowedValues = [20, 50, 100];

            if (!in_array($limit, $allowedValues)) {
                return response()->json(['message' => 'Invalid limit'], 400);
            }

            $authors = Author::query();
            $searchTerm = $request->query('q');

            if ($searchTerm) {
                $authors = $authors->where(function ($query) use ($searchTerm) {
                    $query->where('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $searchTerm . '%');
                });
            }

            $authors = $authors->paginate($limit);

            return AuthorResource::collection($authors);
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(AuthorRequest $request)
        {
            $params = $request->validated();

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $path = $file->store('author_pictures', 'public');
                $params['picture'] = $path;
            }
            $author = Author::create($params);
            return new AuthorResource($author);
        }

        /**
         * Display the specified resource.
         */
        public function show(Author $author)
        {
//            $author->load('books');
            return new AuthorResource($author);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, Author $author)
        {
            //
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Author $author)
        {
            //
        }
    }
