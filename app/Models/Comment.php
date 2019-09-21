<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = 'comments';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function task(){
        return $this->belongsTo('App\Models\Task');
    }
}
