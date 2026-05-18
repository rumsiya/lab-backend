<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;
use App\Models\User;
use App\Models\status;
use App\Models\Test;

class AppointmentTest extends Model
{
    protected $table = 'appointment_tests';
    protected $fillable =['appointment_id','test_id','status_id','staff_id'];

    public function getAppointment(){
        return $this->belongsTo(Appointment::class,'appointment_id','id');
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


}
