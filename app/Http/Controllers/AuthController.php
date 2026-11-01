<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // VULNERABILITY: Weak hashing — MD5 used for passwords
    public function register(Request $request)
    {
        $hashedPassword = md5($request->input('password')); // MD5 is cryptographically broken
        DB::insert("INSERT INTO users (name, email, password) VALUES (?, ?, ?)", [
            $request->input('name'),
            $request->input('email'),
            $hashedPassword,
        ]);
        return response()->json(['message' => 'Registered']);
    }

    // VULNERABILITY: Weak hashing + SQL Injection
    public function login(Request $request)
    {
        $email    = $request->input('email');
        $password = md5($request->input('password')); // MD5 weak hash
        $user = DB::select("SELECT * FROM users WHERE email='$email' AND password='$password'");
        if ($user) {
            session(['user_id' => $user[0]->id]);
            return response()->json(['message' => 'Login OK']);
        }
        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}
