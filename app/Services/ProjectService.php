<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectHasMember;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Jobs\SendEmails;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class ProjectService{

    protected $project;

    public function __construct(Project $project){
        $this->project = $project;
    }

    public function index(){
        
        $id = auth()->id();

        $projects = ProjectHasMember::where('member_id', $id)
                                    ->rightJoin('projects','project_has_members.project_id','=','projects.id')
                                    ->select('projects.id','projects.title','projects.description','projects.background',
                                            'project_has_members.role','project_has_members.star')
                                    ->get();
        
        return $projects;
    }

    public function create(Request $request){

        $project = new Project;
        $project->user_id = auth()->id();
        $project->title = $request->title;
        if(isset($request->description)){
            $project->description = $request->description;
        }
        $project->save();

        $user = User::find(auth()->id());

        $project_id = $project->id;

        $projectHasMember = new ProjectHasMember;
        $projectHasMember->project_id = $project_id;
        $projectHasMember->member_id = auth()->id();
        $projectHasMember->role = true;
        $projectHasMember->save();

        $status = new Status;
        $status->project_id = $project_id;
        $status->title = "Unassigned";
        $status->order = 0;
        $status->save();

        $status = new Status;
        $status->project_id = $project_id;
        $status->title = "Open";
        $status->order = 1;
        $status->save();

        $status = new Status;
        $status->project_id = $project_id;
        $status->title = "Work in Progress";
        $status->order = 2;
        $status->save();

        $status = new Status;
        $status->project_id = $project_id;
        $status->title = "Completed";
        $status->order = 3;
        $status->save();
        
        $background = "/media/project_background/default.jpg";

        return response()->json(["message"=>"success","id"=>$project_id,"title"=>$project->title,"background"=>$background],201);
    }

    public function getProjectDetails($id){
        $project = Project::find($id);
        $role = ProjectHasMember::where('project_id',$id)
                                ->where('member_id',auth()->id())
                                ->select('star','role')
                                ->first();
        $project->star = $role->star;

        $members = DB::table('project_has_members')
                    ->rightJoin('users','project_has_members.member_id','=','users.id')
                    ->where('project_has_members.project_id','=',$id)
                    ->select('users.id','users.name','users.email','project_has_members.role','users.photo_location')
                    ->orderBy('users.name')
                    ->get();

        $date = new Carbon($project->created_at);
        $dt = $date->toFormattedDateString();
        
        $response = array(
            "id" => $project->id,
            "title" => $project->title,
            "description" => $project->description,
            "created_at" => $dt,
            "background" => $project->background,
            "star" => $role->star,
            "role" =>$role->role,
            "members" => $members
        );

        return $response;
    }

    // public function update(Request $request, $id){
    //     $project = Project::find($id) ;

    //     if(isset($request->title)){
    //         $project->title = $request->title;
    //     }

    //     if(isset($request->description)){
    //         $project->description = $request->description;
    //     }
    //     return $project->save();

    // }

    public function delete($id){
        $project = Project::find($id) ;
        $project->delete();
        return response()->json(["message"=>"success","id"=>$id],200);
    }

    public function updateImage(){
    
    }

    public function updateStar(Request $request, $id){
        $change = ($request->star=="1")?true:false;
        $update = ProjectHasMember::where('project_id',$id)
                                    ->where('member_id',auth()->id())
                                    ->update(['star' =>$change]);
        $response = array();
        $response['message'] = "success";
        $response['star'] = $request->star;
        $response['id'] = $id;
        return $response;
    }

    public function updateTitle(Request $request,$id){
        $change = Project::find($id)->update(['title'=> $request->title]);
        return "Success";
    }

    public function updateDescription(Request $request,$id){
        $change = Project::find($id)->update(['description'=> $request->description]);
        return "Success";
    }

    public function updateMember(Request $request,$id,$member_id){
        if($request->admin == 1)
            $add = true;
        else
            $add = false;
        $change = ProjectHasMember::where('project_id',$id)
                                ->where('member_id',$member_id)
                                ->update(['role'=>$add]);
        return response()->json(['message'=>'success','id'=>$member_id],200);
    }

    public function deleteMember($id,$member_id){

        //Remove member from tasks also

        // $update = TaskHasMember::where('project_id',$id)
        //                         ->where('member_id',$member_id)
        //                         ->delete();

        $change = ProjectHasMember::where('project_id',$id)
                                    ->where('member_id',$member_id)
                                    ->delete();
        return response()->json(['message'=>'success','id'=>$member_id],200);
    }


    public function generateReport($id){
        $data = ['title' => 'A pdf is generated'];
        $pdf = PDF::loadView('reportPDF', $data);
        return $pdf;
    }

    public function allUsers($id){
        $pattern = Input::get('pattern');
        /*
        $allUsers = User::where('email','like' , '%' . $pattern . '%')
                        ->orWhere('name', 'like', '%' . $pattern . '%')
                        ->whereNotIn('id')
                        ->select('id','name','email')
                        ->orderBy('id')
                        ->get();


                        

        */
        $members = DB::table('project_has_members')
        ->where('project_has_members.project_id','=',$id)
        ->select('member_id')
        ->get();

        $ids = array();

        foreach($members as $member => $id){
            array_push($ids,$member);
        }

        $allUsers = User::where('email','like' , '%' . $pattern . '%')
                        ->orWhere('name', 'like', '%' . $pattern . '%')
                        ->whereNotIn('id',$ids)
                        ->select('id','name','email')
                        ->orderBy('name')
                        ->get();

        return response()->json(["message"=>"success","members"=>$allUsers],200);
    }

    public function invite(Request $request, $id){
        $ids = $request->membersList;
        $message = $request->inviteMessage;
        //$member_id = Input::get("id");
        $project = Project::find($id);
        $project->message = $message;

        foreach($ids as $member_id){
            $user = User::find($member_id);
            $project->url = "http://site.test/project/".$id."/member/".$member_id;
            SendEmails::dispatch($user,"inviteToProject",$project);
        }
    }

    public function addMember($id,$member_id){
        $projectHasMember = new ProjectHasMember;
        $projectHasMember->project_id = $id;
        $projectHasMember->member_id = $member_id;
        $projectHasMember->save();
        return "saved";
    }

}

?>