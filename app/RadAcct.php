<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadAcct extends Model
{
    //
    protected $table='radacct';
    public $primaryKey=null;
    public $incrementing=false;

    protected  $fillable=['acctsessionid','acctuniqueid','username'];

    public function user(){
        return $this->hasMany(RadCheck::class,'username','username');
    }
}
