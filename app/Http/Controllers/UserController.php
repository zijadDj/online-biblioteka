<?php

namespace App\Http\Controllers;

use App\Events\LibrarianCreated;
use App\Http\Requests\UserRequest;
use App\Http\Requests\FilterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(FilterUserRequest $request)
    {
        $data = $request->validated();

        $roleFlag = $data['role'] === 'librarian' ? User::ROLE_LIBRARIAN : User::ROLE_STUDENT;
        $perPage = $data['per_page'] ?? 20;
        $search = strtolower($data['search-value'] ?? '');

        $query = User::query()
            ->where('is_librarian', $roleFlag);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(surname) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
            });
        }

        return response()->json($query->paginate($perPage));
    }


    public function store(UserRequest $request): JsonResponse
    {
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
            'is_librarian' => $data['role'] === 'librarian' ? User::ROLE_LIBRARIAN : User::ROLE_STUDENT,
            'password' => Hash::make($data['password']),
        ]);

        if ($data['role'] === 'librarian') {
            event(new LibrarianCreated($user));
        }

        return response()->json([
            'message' => 'User created successfully.',
            'user' => $user,
        ], 201);
    }
    public function show(User $user)
    {
        return response()->json(['user' => $user]);
    }


    public function updateAvatar(Request $request, User $user)
    {
        $request->validate([
            'photo' => 'required|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        $file = $request->file('photo');

        if ($user->photo_path && Storage::disk('public')->exists($user->photo_path)) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $filePath = $file->store('photos', 'public');

        $user->update(['photo_path' => $filePath]);

        return response()->json(['user' => $user]);
    }

    public function update(UserRequest $request, User $user)
    {
        $params = $request->validated();

        $user->update($params);
        return response()->json(['user' => $user]);
    }

    public function destroy(User $user){
        if($user->id === auth()->id()){
            return response()->json([
                'error' => 'You cannot delete your own account'
            ], 403);
        }

        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}
