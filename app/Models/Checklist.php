<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    //
    protected $table = 'checklists';
    protected $fillable = ['completed'];
    public $timestamps = false;

    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }
}
