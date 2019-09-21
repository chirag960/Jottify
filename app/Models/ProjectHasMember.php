<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectHasMember extends Model
{
    //
    protected $table = 'project_has_members';
    public $timestamps = false;

    protected $fillable = [
        'role','star'
    ];
}
