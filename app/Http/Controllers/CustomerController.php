<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'username' => 'required|unique:customers,username',
            'phone' => 'required|unique:customers,phone',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $request['password'] = Hash::make($request->password);

        $user = Customer::create($request->all());

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

        $user = Customer::where('username', $request->username)->first();

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
                'token' => $user->createToken($request->device_name, ['Customer'])->plainTextToken
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
        if ($request->user()->tokenCan('Customer')) {
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
        if ($request->user()->tokenCan('Customer')) {
            $request->user()->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => "Logged out",
            ]);

        }else{
            return response()->json([
                'status' => false,
                'message' => trans('auth.failed')
            ], 404);
        }
    }
}
