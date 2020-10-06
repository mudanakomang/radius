<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RadUserGroup extends Model
{
    //
    protected $table='radusergroup';
    protected $fillable=['username','groupname','harga'];
    public $timestamps=false;
    protected $primaryKey = null;
    public $incrementing = false;


    public function user(){
        return $this->hasMany('\App\RadCheck','username','username');
    }

    public function radgroupcheck(){
        return $this->hasMany(RadGroupCheck::class,'groupname','groupname');
    }
    public function radgroupreply(){
        return $this->hasMany(RadGroupReply::class,'groupname','groupname');
    }

}
