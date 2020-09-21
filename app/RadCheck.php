<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadCheck extends Model
{
    //
    protected $table='radcheck';
    protected $fillable=['username','attribute','op','value'];
    public $timestamps=false;

    public function userGroup(){
        return $this->belongsTo(RadUserGroup::class,'username','username');
    }

}
