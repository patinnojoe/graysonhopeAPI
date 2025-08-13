<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    //

    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'nullable|string',
            'phone_number' => 'required|string',
            'extra_info' => 'nullable|string',
            'skills' => 'nullable|string',
            "volunteer_role" => 'nullable|string',
            'about' => 'nullable|string',
            'email' => "nullable|string|email",

        ]);


        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = empty($request->role) ? 'user' : $request->role;




        // Set default password if not provided
        $password = $request->password ? Hash::make($request->password) : Hash::make('specialVolunteer22');

        // Create user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'skills' => $request->skills,
            'volunteer_role' => $request->volunteer_role,
            'email' => $request->email,
            'extra_info' => $request->extra_info,
            'about' => $request->about,
            'role' => $role,
            'password' => $password
        ]);

        // Create a Sanctum token for the user
        // $token = $user->createToken('admin-token')->plainTextToken;




        // Mail::to($user->email)->send(new UserRegisteredMail($user));
        // Mail::to('support@edolanguageacademy.com')->send(new AdminUserRegisteredMail($user));
        // Return success response
        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            // 'token' => $token,
        ], 201);
    }


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);



        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Invalid email or password'], 401);
        }

        // Create a Sanctum token for the user
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }
    public function verifyUser(Request $request)
    {
        $user = $request->user();
        return $user;
    }

    // update user
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'address' => 'nullable|string',
            'phone_number' => 'sometimes|string',
            'extra_info' => 'nullable|string',
            'skills' => 'nullable|string',
            'volunteer_role' => 'nullable|string',
            'about' => 'nullable|string',
            'email' => 'sometimes|email',
            'password' => 'nullable|string|min:6',
            'role' => 'sometimes|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update only provided fields
        $user->fill($request->only([
            'first_name',
            'last_name',
            'address',
            'phone_number',
            'extra_info',
            'skills',
            'volunteer_role',
            'about',
            'email',
            'role'
        ]));

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    //delete user
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);

            // Delete the user
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
