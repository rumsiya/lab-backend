<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request){

        $validated = $request->validate([
            'email' => 'required',
            'password' =>  'required|min:5|max:8',
        ]);

        if($validated){
            $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password']
            ];

            $token = JWTAuth::attempt($credentials);

            if($token){
                return response()->json([
                    'success' => true,
                    'user' => User::where('email',$validated['email'])->first(),
                    'token' => $token,
                    'message' => 'successully login'
                ]) ;
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'login error'
                ]);
            }
        }

    }

    public function register(Request $request){
        try{
            $validated = $request->validate([
                'username' => 'required|min:3|max:10',
                'email' => 'required|unique:users,email',
                'password' =>  'required|min:5|max:10',
                'phone' => 'required|digits:10',
                'age' => 'required|numeric',
                'gender' => 'required|boolean',
            ]);

            if($validated){
                $data = [
                    'username' => $validated['username'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => 1,
                    'gender' =>$validated['gender'],
                    'phone' =>$validated['phone'],
                    'age' =>$validated['age'],
                ];

                $user = User::create($data);
                $token = JWTAuth::fromUser($user);
                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'token' => $token,
                    'message' => 'successfully registered'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid validation'
                ]);
            }
        }catch( \Exception $e){
            return response()->json([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
        }


    }
}
