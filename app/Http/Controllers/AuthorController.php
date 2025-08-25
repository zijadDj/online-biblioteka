<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\AuthorRequest;
    use App\Http\Resources\AuthorResource;
    use App\Models\Author;
    use App\Models\Book;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Storage;

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

            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
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
        public function update(AuthorRequest $request, Author $author)
        {
            $params = $request->validated();
            $attachBookIds = $params['book_ids'];
            $detachBookIds = $params['remove_book_ids'];
            unset($params['remove_book_ids']);
            unset($params['book_ids']);

            if ($attachBookIds) {
                Book::whereIn('id', $attachBookIds)->update(['author_id' => $author->id]);
            }
            if ($detachBookIds) {
                Book::whereIn('id', $detachBookIds)->update(['author_id' => null]);
            }

            $author->update($params);
            $author->load('books');
            return new AuthorResource($author);
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Author $author)
        {
            //
        }


        public function updateAvatar(Author $author, Request $request)
        {
            $request->validate([
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);
            $file = $request->file('picture');
            if ($author->picture && Storage::disk('public')->exists($author->picture)) {
                Storage::disk('public')->delete($author->picture);
            }
            $path = $file->store('author_pictures', 'public');
            $author->update(['picture' => $path]);
            return new AuthorResource($author);
        }
    }
