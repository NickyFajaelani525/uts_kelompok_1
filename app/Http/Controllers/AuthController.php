<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $hashedPassword = Hash::make($request->input('password'));

        DB::insert(
            "INSERT INTO users (name, email, password) VALUES (?, ?, ?)",
            [
                $request->input('name'),
                $request->input('email'),
                $hashedPassword,
            ]
        );

        return response()->json([
            'message' => 'Registered',
        ]);
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = DB::table('users')
            ->where('email', $email)
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            session(['user_id' => $user->id]);

            return response()->json([
                'message' => 'Login OK',
            ]);
        }

        return response()->json([
            'error' => 'Invalid credentials',
        ], 401);
    }
}
