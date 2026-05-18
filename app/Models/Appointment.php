<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Test;
use App\Models\Status;
use App\Models\AppointmentTest;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $fillable = ['booking_id','patient_id','test_id','staff_id','status_id','booking_date','prefered_time'];


    public function getPatient(){
        return $this->belongsTo(User::class,'patient_id','id');
    }

    public function getTest(){
        return $this->belongsTo(Test::class,'test_id','id');
    }

    public function getStatus(){
        return $this->belongsTo(Status::class,'status_id','id');
    }

    public function getStaff(){
        return $this->belongsTo(User::class,'staff_id','id');
    }

    public function getAppointmentTest(){
        return $this->hasMany(AppointmentTest::class,'id','appointment_id');

    }
}
