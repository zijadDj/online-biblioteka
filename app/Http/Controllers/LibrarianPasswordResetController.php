<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class LibrarianPasswordResetController extends Controller
{
    public function request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:librarians,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        $status = Password::broker('librarians')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to email.']);
        }

        return response()->json([
            'message' => 'Failed to send password reset link.'
        ], 500);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:librarians,email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $status = Password::broker('librarians')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($librarian, $password) {
                $librarian->password = Hash::make($password);
                $librarian->save();

                Auth::guard('librarian')->login($librarian);


            }
        );



        if ($status === Password::PASSWORD_RESET) {
            return redirect('/librarian/dashboard')->with('success', 'Dobrodošli nazad!');

        }

        return redirect()->back()->withErrors(['token' => 'Token je nevažeći ili istekao.']);
    }

    public function showForm(Request $request)
    {
        if (! $request->filled(['token', 'email'])) {
            abort(404);
        }
        return view('librarian.reset-password');
    }

}
