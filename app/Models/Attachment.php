<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    protected $table = 'attachments';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo('App\Models\User');
    }
}
