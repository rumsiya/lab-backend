<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppointmentTest;
use App\Models\Appointment;

class AppointmentTestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = AppointmentTest::all();
        return response()->json([
            'success' => true,
            'appointment_test' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = AppointmentTest::with('getAppointment')->with('getTest.getUnit')->with('getStatus')->with('getStaff')->where('appointment_id',$id)->get();
        $appointment =Appointment::with('getPatient')->with('getStatus')->with('getStaff')->find($id);
        return response()->json([
            'success' => true,
            'appointment_test' => $data,
            'appointment'=> $appointment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
