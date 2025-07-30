<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
      if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([['message' => 'Your credentials are incorrect, please try again']], 400);
        }
        if (!$user->is_librarian) {
            return response()->json([['message' => 'This user cannot perform this operation']], 403);
        }
        
        $tokenName = 'Login token';
        $abilities = ['*'];
        $expiresAt = now()->addMonth(); 
        $token = $user->createToken($tokenName, $abilities, $expiresAt);
        $token = $token->plainTextToken;

        return response()->json(['message' => 'success', 'token' => $token],200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message'=> 'You have been logged out successfully'],200);
    }
}
