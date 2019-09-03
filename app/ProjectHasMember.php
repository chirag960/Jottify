<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectHasMember extends Model
{
    //
    protected $table = 'project_has_member';
    public $timestamps = false;

    protected $fillable = [
        'role','star'
    ];
}
