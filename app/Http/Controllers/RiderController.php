<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RiderController extends Controller
{
    //register
    public function register(Request $request)
    {
        if (!$request->user()->tokenCan('Admin')) {
            return response()->json([
                'status' => false,
                'message' => "page not found",
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:riders,email',
            'username' => 'required|unique:riders,username',
            'phone' => 'required|unique:riders,phone',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $request['password'] = Hash::make($request->password);

        $user = Rider::create($request->all());

        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => 'Registration successfull.'
        ], 201);
    }

    //verify
    public function verify(Request $request)
    {
    }

    //login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Rider::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => trans('auth.failed')
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'user' => $user,
                'token' => $user->createToken($request->device_name, ['Rider'])->plainTextToken
            ],
            'message' => 'Login successfull.'
        ]);
    }

    //recover
    public function recover(Request $request)
    {
    }

    //reset
    public function reset(Request $request)
    {
    }

    //user
    public function user(Request $request)
    {
        if ($request->user()->tokenCan('Rider')) {
            return response()->json([
                'status' => true,
                'data' => [
                    'user' => $request->user(),
                ],
            ]);

        }else{
            // $request->user()->tokens()->delete();
            return response()->json([
                'status' => false,
                'message' => trans('auth.failed')
            ], 404);
        }
    }

    //logout
    public function logout(Request $request)
    {
    }
}
