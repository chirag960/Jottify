<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskHasMember extends Model
{
    //
    protected $table = 'task_has_members';
    public $timestamps = false;

    protected $fillable = [
        'role'
    ];
}
