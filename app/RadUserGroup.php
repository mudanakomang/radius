<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadUserGroup extends Model
{
    //
    protected $table='radusergroup';
    protected $fillable=['username','groupname'];
    public $timestamps=false;
    protected $primaryKey = null;
    public $incrementing = false;


    public function user(){
        return $this->hasMany('\App\RadCheck','username','username');
    }

    public function radgroupcheck(){
        return $this->belongsTo(RadGroupCheck::class,'groupname','groupname');
    }
    public function radgroupreply(){
        return $this->belongsTo(RadGroupReply::class,'groupname','groupname');
    }
}
