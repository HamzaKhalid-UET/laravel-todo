<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function storeUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users|string|max:255',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $request->merge(['password' => Hash::make($request->password)]);
        $user = ModelsUser::create($request->all());
        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }
    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $user = ModelsUser::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid password'], 401);
        }
        $token = Helper::generateToken($user);
        return response()->json(['message' => 'Login successful', 'token' => $token], 200);
    }
}
