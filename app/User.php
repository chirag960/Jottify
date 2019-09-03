<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{

    protected $table = 'users';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','photo_location'
    ];

   // public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects(){
         $projects = $this->belongsToMany('App\Project', 'project_has_member', 'member_id', 'project_id');
         return $projects;
    }

    public function tasks(){
        return $this->belongsToMany('App\Task', 'task_has_member', 'member_id', 'task_id');
    }

    public function attachments(){
        return $this->hasMany('App\Attachment');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }
    
}
