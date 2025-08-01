<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class UserController extends Controller
{
    use AuthorizesRequests;

    public function store(CreateUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $data = $request->validated();

        $photoPath = "default.jpg";

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos', 'public');
        }

        $user = User::create([
            'name' => $data['first_name'],
            'surname' => $data['last_name'],
            'email' => $data['email'],
            'jmbg' => $data['jmbg'],
            'photo_path' => $photoPath,
            'is_librarian' => $data['role'] === 'librarian' ? '1' : '0',
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }
}
