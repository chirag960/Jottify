<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    protected $table = 'attachment';

    public function user(){
        return $this->belongsTo('App\User');
    }
    
}
