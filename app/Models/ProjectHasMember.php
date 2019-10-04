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

    public function checkProjectMember($project_id){
        return $this->where([['member_id','=',auth()->id()],['project_id','=',$project_id]])
                    ->first();
    }

    public function checkProjectAdmin($project_id){
        return $this->where([['member_id','=',auth()->id()],['project_id','=',$project_id],['role','>',0]])
                    ->first();
    }

    public function checkProjectCreator($project_id){
        return $this->where([['member_id','=',auth()->id()],['project_id','=',$project_id],['role','=',2]])
                    ->first();
    }

    public function getProjects($member_id){
        return $this->where('member_id', $member_id)
                                    ->rightJoin('projects','project_has_members.project_id','=','projects.id')
                                    ->select('projects.id','projects.title','projects.description','projects.background',
                                            'project_has_members.role','project_has_members.star')
                                    ->get();
    }

    public function create($project_id,$member_id,$role){
        $this->project_id = $project_id;
        $this->member_id = auth()->id();
        $this->role = 2;
        $this->save;
        return $this;
    }

    public function getMembersDetails($project_id){
        return $this->rightJoin('users','project_has_members.member_id','=','users.id')
            ->where('project_has_members.project_id','=',$project_id)
            ->select('users.id','users.name','users.email','project_has_members.role','users.photo_location')
            ->orderBy('users.name')
            ->get();
    }

    public function getMemberIds($project_id){
        return $this->where('project_has_members.project_id','=',$project_id)
                    ->pluck('member_id')
                    ->toArray();
    }

    public function getRole($project_id){
        return $this->where('project_id',$project_id)
                    ->where('member_id',auth()->id())
                    ->first()->role;
    }

    public function getRoleAndStar($project_id){
        return $this->where('project_id',$project_id)
                    ->where('member_id',auth()->id())
                    ->select('star','role')
                    ->first();
    }

    public function setStar($project_id,$change){
        $this->where('project_id',$project_id)
                ->where('member_id',auth()->id())
                ->update(['star' =>$change]);
    }

    public function setAdmin($project_id,$member_id,$role){
        $this->where('project_id',$project_id)
                ->where('member_id',$member_id)
                ->update(['role'=>$role]);
    }

    public function deleteMember($project_id,$member_id){
        $this->where('project_id',$project_id)
                ->where('member_id',$member_id)
                ->delete();
    }

    public function searchProjects($pattern){
        return $this->rightJoin('projects','project_has_members.project_id','=','projects.id')
                    ->where('project_has_members.member_id','=',auth()->id())
                    ->where('projects.title','like','%'.$pattern.'%')
                    ->select(['id','title'])
                    ->get();
    }

}
