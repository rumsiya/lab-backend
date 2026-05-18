<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Test;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $test = Test::with('getUnit')->get();
        return response()->json([
            'success' => true,
            'tests' => $test
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

        $validated = $request->validate([
            'test_name' => 'required|min:3|max:20',
            'normal_min'=> 'required|numeric',
            'normal_max' => 'required|numeric',
            'unit_id'=> 'required|numeric',
            'price'=> 'required|decimal:0,2',
            'description' => 'required|min:2'
        ]);

        if($validated){
            $data = [
                'test_name'=> $validated['test_name'],
                'normal_min' => $validated['normal_min'],
                'normal_max' => $validated['normal_max'],
                'unit_id' => $validated['unit_id'],
                'price' => $validated['price'],
                'description' => $validated['description']
            ];

            $test = Test::create($data);
            if($test){
                return response()->json([
                    'success' => true,
                    'test' => $test->load('getUnit')
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'something went wrong'
                ]);
            }
        }
            //code...
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $test = Test::find($id);
        return response()->json([
            'success' => true,
            'test' => $test
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $test = Test::find($id);
        $validated = $request->validate([
            'test_name' => 'required|min:3|max:20',
            'normal_min'=> 'required|numeric',
            'normal_max' => 'required|numeric',
            'unit_id'=> 'required|numeric',
            'price'=> 'required|decimal:0,2',
            'description' => 'required|min:2'
        ]);
        if($validated){
            $data = [
                'test_name'=> $validated['test_name'],
                'normal_min' => $validated['normal_min'],
                'normal_max' => $validated['normal_max'],
                'unit_id' => $validated['unit_id'],
                'price' => $validated['price'],
                'description' => $validated['description']
            ];

            if($test->update($data)){
                return response()->json([
                    'success' => true,
                    'test' => Test::with('getUnit')->find($id),
                    'message'=> 'successfully updated'
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message'=> 'Error while updation'
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $test = Test::find($id);
        if($test->delete()){
            return response()->json([
                'success' => true,
                'message' => 'successfully deleted'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete'
            ]);
        }

    }
}
