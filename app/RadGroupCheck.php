<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadGroupCheck extends Model
{
    //
    protected $table='radgroupcheck';
    protected $fillable=['groupname','attribute','op','value'];
    public $timestamps=false;
    public $incrementing = false;

    public function usergroup(){
        return $this->hasMany(RadUserGroup::class,'groupname','groupname');
    }
}
