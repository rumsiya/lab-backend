<?php

namespace App\services;
use App\Models\AppointmentTest;
use Illuminate\Support\Facades\Auth;

class AppointmentTestServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function storeTests($tests = [], $appointment_id){
        $result = [];
        foreach($tests as $t){

            $data = [
                'appointment_id' => $appointment_id,
                'test_id' => $t,
                'status_id'=> 2,
                'staff_id'=>1

            ];
            $testCreated = AppointmentTest::create($data);
            if($testCreated){
                $result[]=$testCreated;
            }
        }
        return $result;
    }

    public function updateStatus($status,$appointment_id,$test_id){
        $appoin = AppointmentTest::where('appointment_id',$appointment_id)->where('test_id',$test_id)->first();
        if($appoin){
            $data = [
                'status_id' => $status
            ];
            if($appoin->update($data)){
                return true;
            }
        }
        return false;

    }

    public function updateStaff($appointment_id,$test_id){
        $appoin = AppointmentTest::where('appointment_id',$appointment_id)->where('test_id',$test_id)->first();
        if($appoin){
            $data = [
                'staff_id' => Auth::user()->id
            ];
            if($appoin->update($data)){
                return true;
            }
        }
        return false;

    }
}
