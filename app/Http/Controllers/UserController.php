<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::with('getRole')->get();
        return response()->json([
            'success' => true,
            'users' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|min:3|max:10',
            'email' => 'required|unique:users,email',
            'role' => 'required|numeric',
            'phone' => 'required|digits:10',
            'age' => 'required|numeric|min:18|max:60',
            'gender' => 'required|boolean',
        ]);

        if($validated){
            $data = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['phone'],
                'age' => $validated['age'],
                'gender' => $validated['gender']
            ];
            $user = User::create($data);
            if($user){
                return response()->json([
                    'success' => true,
                    'user' => $user->load('getRole'),
                    'message' => 'Successfully created',
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Error while creation',
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $validated = $request->validate([
            'username' => 'required|min:3|max:10',
            'email' => 'required',
            'role' => 'required|numeric',
            'phone' => 'required|digits:10',
            'age' => 'required|numeric|min:18|max:60',
            'gender' => 'required|boolean',
        ]);

        if($validated){
            $data = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['phone'],
                'age' => $validated['age'],
                'gender' => $validated['gender']
            ];
            if($user->update($data)){
                return response()->json([
                    'success' => true,
                    'user' => $user->load('getRole'),
                    'message' => 'Successfully updated',
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Error while updation',
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if($user->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Error while deletion'
            ]);
        }
    }
}
