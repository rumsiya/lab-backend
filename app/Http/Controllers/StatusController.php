<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $status = Status::all();
        return response()->json([
            'success' => true,
            'status' => $status
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|min:3|max:20'
        ]);
        if($validated){
            $data = [
                'status' => $validated['status']
            ];

            $status = Status::create($data);
            if($status){
                return response()->json([
                    'success' => true,
                    'status' => $status,
                    'message' => 'successfully created'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'error while creation'
                ]);
            }

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $status = Status::find($id);
        return response()->json([
            'success' => true,
            'status' => $status
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status = Status::find($id);
        $validated = $request->validate([
            'status' => 'required|min:3|max:20'
        ]);
        if($validated){
            $data = [
                'status' => $validated['status']
            ];

            if($status->update($data)){
                return response()->json([
                    'success' => true,
                    'status' => $status,
                    'message' => 'successfully updated'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'error while updated'
                ]);
            }

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $status = Status::find($id);
        if($status->delete()){
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
