<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Appointment;
use App\Models\Test;
use App\Models\Unit;

class Report extends Model
{
    protected $table = 'reports';
    protected $fillable =['appointment_id','test_id','min_result','max_result','unit_id','description','result_file'];

    public function getAppointment(){
        return $this->belongsTo(Appointment::class,'appointment_id','id');
    }

    public function getTest(){
        return $this->belongsTo(Test::class,'test_id','id');
    }

    public function getUnit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    }

}
