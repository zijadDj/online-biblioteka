<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page',20);
        $perPage= in_array($perPage,[20,50,100]) ? $perPage : 20;
        $search = strtolower($request->input('search-value',''));
        $query = Category::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }
        return response()->json($query->paginate($perPage));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:500',
            'description' => 'required|string|max:500',
            'icon' => 'nullable|image|max:5120',
            'book_ids'=>'nullable|array',
        ]);
        $iconPath = "default.jpg";

        if ($request->hasFile('icon')) {
            $iconPath = $request->file('icon')->store('icons', 'public');
        }
        $category = Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'icon_path' => $iconPath,
        ]);

        if(!empty($validated['book_ids'])){
            $category->books()->attach($validated['book_ids']);
        }
        return response()->json([
            'message' => 'Category created successfully.',
            'category' => $category]
            , 201);
    }

    public function icon(Category $category)
    {
        return response()->file(storage_path('app/public/' . $category->icon_path));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
