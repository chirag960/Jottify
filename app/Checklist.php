<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    //
    protected $table = 'checklist';
    protected $fillable = ['completed'];
    public $timestamps = false;

    public function task()
    {
        return $this->belongsTo('App\Task');
    }
}
