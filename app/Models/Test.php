<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Unit;

class Test extends Model
{
    protected $tablle  = 'tests';
    protected $fillable = ['test_name','normal_min','normal_max','unit_id','price','description'];

    public function getUnit(){
        return $this->belongsTo(Unit::class,'unit_id','id');
    }
}
