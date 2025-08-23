<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $search= $request->input('search');

        $query=Policy::query()
            ->when($search, function ($q) use ($search){
            $q->where('name','like','%'.$search.'%');
    });

    return response()->json($query->get());
    }

    public function show(Policy $policy)
    {
        return response()->json($policy);
    }

    public function store(Request $request)
    {
        $validated=$request->validate([
            'name' => 'required|string|max:255',
            'period' => 'required|integer|min:1'
        ]);

        $policy=Policy::create($validated);

        return response()->json(['message'=>'Policy created','policy'=>$policy]);
    }

    public function update(Request $request, Policy $policy)
    {
        $validated=$request->validate([
            'period' => 'integer|min:1'
        ]);

        $policy->update($validated);
        return response()->json(['message'=>'Policy period updated','policy'=>$policy]);

    }

    public function destroy(Policy $policy)
    {
        $policy->delete();
        return response()->json(['message'=>'Policy deleted']);
    }
}
