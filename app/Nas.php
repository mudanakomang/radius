<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nas extends Model
{
    //
    protected $table='nas';
    protected $fillable=['nasname','shortname','type','ports','secret','server','community','description'];
    public $timestamps=false;
}
