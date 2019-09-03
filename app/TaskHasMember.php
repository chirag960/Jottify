<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskHasMember extends Model
{
    //
    protected $table = 'task_has_member';
    public $timestamps = false;

    protected $fillable = [
        'role'
    ];
}
