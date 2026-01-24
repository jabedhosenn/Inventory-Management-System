<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            // Registration logic here (e.g., create user in database)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                // 'password' => bcrypt($validated['password']),
                'password' => $validated['password'],
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => $user,
                'token' => $token,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (!Auth::attempt($validated)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $user = $request->user();


            if (!$user || !Hash::check($validated['password'], $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => $user,
                'token' => $token,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated',
                ], 401);
            }
            $user->currentAccessToken()->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Logout successful',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
