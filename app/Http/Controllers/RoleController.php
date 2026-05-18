<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json([
            'success'=> true,
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role_name' => 'required|min:3|max:10'
        ]);

        if($validated){
            $data = [
                'role_name' => $validated['role_name']
            ];

            $role = Role::create($data);
            if($role){
                return response()->json([
                    'success' => true,
                    'role' => $role
                ]);
            }else{
                return response()->json([
                    'success' => false

                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);
        return response()->json([
            'success' => true,
            'role' => $role
        ]) ;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);
        $validated = $request->validate([
            'role_name' => 'required|min:3|max:10'
        ]);

        if($validated){
            $data= [
                'role_name' => $validated['role_name']
            ];
            if($role->update($data)){
                return response()->json([
                    'success' => true,
                    'role' => Role::find($id)
                ]);
            }else{
                  return response()->json([
                    'success' => false,
                    'message' => 'something went wrong'
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);
        if($role->delete()){
            return response()->json([
                'success' => true,
                'role' => $role
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'something went wrong'
            ]);
        }

    }
}
