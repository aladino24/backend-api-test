<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'age' => 'required|integer|min:18',
            'membership_status' => 'required|string|in:active,inactive',
            'role' => 'nullable|string|exists:roles,name', // Validasi role jika ada
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password, // Password akan di-hash otomatis di model
                'age' => $request->age,
                'membership_status' => $request->membership_status,
            ]);

            if ($request->has('role')) {
                $role = Role::where('name', $request->role)->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                } else {
                    return response()->json(['error' => 'Role not found'], 400);
                }
            }

            // Menghasilkan JWT token setelah berhasil registrasi
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User registered successfully!',
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User registration failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        // Validasi input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Ambil kredensial dari request
        $credentials = $request->only('email', 'password');

        // Coba autentikasi dengan JWTAuth
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        // Ambil data pengguna yang sedang login
        $user = JWTAuth::user();

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }
}
