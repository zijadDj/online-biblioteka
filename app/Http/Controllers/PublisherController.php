<?php

    namespace App\Http\Controllers;

    use App\Models\Publisher;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;

    class PublisherController extends Controller
    {
        /**
         * Display a listing of the resource.
         */
        public function index()
        {
            $validated = request()->validate([
                'q' => ['nullable', 'string'],
                'paginate' => ['nullable', 'integer', Rule::in([20, 50, 100])],
            ]);
            $search = trim($validated['q'] ?? '');
            $perPage = $validated['paginate'] ?? 20;

            $query = Publisher::query();
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            }
            return response()->json($query->paginate($perPage));
        }

        /**
         * Store a newly created resource in storage.
         */
        public function store(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'email' => 'nullable|email|max:255',
                'phone_number' => 'nullable|string|max:255',
                'established_year' => 'nullable|integer'
            ]);
            $publisher = Publisher::create($validated);

            return response()->json([
                'message' => "Publisher created successfully.",
                "publisher" => $publisher
            ], 201);
        }

        /**
         * Display the specified resource.
         */
        public function show(Publisher $publisher)
        {
            return response()->json($publisher);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, Publisher $publisher)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'website' => 'nullable|url|max:255',
                'email' => 'nullable|email|max:255',
                'phone_number' => 'nullable|string|max:255',
                'established_year' => 'nullable|integer'
            ]);
            $publisher->update($validated);
            return response()->json([
                "message" => "Publisher " . $publisher->name . " updated successfully",
                "publisher" => $publisher
            ], 201);
        }

        /**
         * Remove the specified resource from storage.
         */
        public function destroy(Publisher $publisher)
        {
            $publisher->delete();
            return response()->json([
                "message" => "Publisher: " . $publisher->name . " deleted successfully"
            ], 200);
        }
    }
