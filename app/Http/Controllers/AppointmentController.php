<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Services\AppointmentTestServices;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $appointmentTests ;
    public function __construct(AppointmentTestServices $appointmentT){
        $this->appointmentTests = $appointmentT;

    }

    public function index()
    {
        if(Auth::user()->role==1 || Auth::user()->role==2){
            $appointments  = Appointment::with('getPatient')->with('getStaff')->with('getStatus')->get();

        }else{
            $appointments  = Appointment::with('getPatient')->with('getStaff')->with('getStatus')->
                            whereHas('getPatient',function($q){
                                $q->where('id',Auth::user()->id);
                            })->get();

        }
        return response()->json([
            'success' => true,
            'appointment' => $appointments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $validated = $request->validate([
                'booking_date'=>'required|date',
                'prefered_time' => 'required'
            ]);

            if($validated){
                $patient_id = Auth::user()->id;
                $booking_id = 'PAT-00'.$patient_id.'-'.random_int(1000000,9000000);
                $data = [
                    'booking_id' => $booking_id,
                    'patient_id' => $patient_id,
                    'status_id' => 2,
                    'booking_date' => $validated['booking_date'],
                    'prefered_time' => $validated['prefered_time'],
                    'staff_id' =>1
                ];
                $appointm = Appointment::create($data);
                if($appointm){
                    $test_ids = $request->test_ids;
                    $ap_test_res = $this->appointmentTests->storeTests($test_ids,$appointm->id);
                    if($ap_test_res){
                         DB::commit();

                        return response()->json([
                        'success' => true,
                        'appointment' => $appointm->load('getStatus')->load('getStaff')->load('getPatient'),
                        'message' => 'Your appointment is generated'
                        ]);

                    }else{
                        DB::rollBack();
                        return response()->json([
                            'error' => "Error to store test"
                        ], 500);
                    }


                }else{
                    return response()->json([
                        'success' => false,
                        'message' => 'Error while appointment'
                    ]);
                }
            }
            //code...
        } catch (\Exception $e) {
            DB::rollBack();

             return response()->json([
                'error' => $e->getMessage()
            ], 500);

        }

    }

        /**
         * Display the specified resource.
         */
        public function show(string $id)
        {
            $data = Appointment::find($id);
            return response()->json([
                'success' => true,
                'appointment' => $data
            ]);
        }

        /**
         * Update the specified resource in storage.
         */
        public function update(Request $request, string $id)
        {
            try {

                $appointment = Appointment::find($id);
                $validated = [
                    'booking_date'=>'required|date',
                    'status_id' => 'numeric',
                    'staff_id' => 'numeric'
                ];

                if($validated){
                    $data = [
                        'status_id' => $validated['status_id'],
                        'booking_date' => date('Y-m-d H:i:s'),
                        'staff_id' => $validated['staff_id']
                    ];

                    $appointm = Appointment::create($data);
                    if($appointm){
                        return response()->json([
                            'success' => true,
                            'appointment' => $appointm,
                            'message' => "appointment is updated for patient.' ' $request->patient_id"
                        ]);

                    }else{
                        return response()->json([
                            'success' => false,
                            'message' => 'Error while updation'
                        ]);
                    }
                }
            } catch (\Exception $e) {
                return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                ]);
            }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Appointment::find($id);
        if($data->delete()){
            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted'
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete'
            ]);
        }
    }
}
