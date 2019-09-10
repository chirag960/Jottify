<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $table = 'statuses';
    public $timestamps = false;

    protected $fillable = ['order','title','archived'];

}
