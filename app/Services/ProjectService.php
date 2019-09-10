<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ProjectHasMember;
use App\Project;
use App\Status;
use App\User;
use App\Jobs\SendEmails;
use PDF;
use Validator;

class ProjectService{

    protected $project;

    public function __construct(Project $project){
        $this->project = $project;
    }

    public function index(){
        
        $id = auth()->id();
        $projects = User::find($id)->projects;
        $star = ProjectHasMember::where('member_id', $id)
                                ->where('star',true)
                                ->select('project_id')
                                ->get();
        $response['projects'] = $projects;
        $response['star'] = $star;
        return $response;
    }

    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:30|required',
            'description' => 'sometimes|max:255',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 400);
        }
        else{
        $project = new Project;
        $project->user_id = auth()->id();
        $project->title = $request->title;
        if(isset($request->description)){
            $project->description = $request->description;
        }
        $project->save();

        $user = User::find(auth()->id());
        SendEmails::dispatch($user,"inviteToProject",$project);

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

    }

    public function getProjectDetails($id){
        $project = Project::find($id);
        $role = ProjectHasMember::where('project_id',$id)
                                ->where('member_id',auth()->id())
                                ->select('star','role')
                                ->first();
        $project->star = $role->star;

        $members = DB::table('project_has_member')
                    ->rightJoin('users','project_has_member.member_id','=','users.id')
                    ->where('project_has_member.project_id','=',$id)
                    ->select('users.id','users.name','users.email','project_has_member.role','users.photo_location')
                    ->get();
        $response = array(
            "id" => $project->id,
            "title" => $project->title,
            "description" => $project->description,
            "timestamp" => $project->timestamp,
            "background" => $project->background,
            "star" => $role->star,
            "role" =>$role->role,
            "members" => $members
        );

        //dd($response);
        return $response;
    }

    public function update(Request $request, $id){
        $project = Project::find($id) ;

        if(isset($request->title)){
            $project->title = $request->title;
        }

        if(isset($request->description)){
            $project->description = $request->description;
        }
        return $project->save();

    }

    public function delete(Request $request, $id){
        $project = Project::find($id) ;
        return $project->delete();
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
        $change = ProfileHasMember::where('project_id',$id)
                                ->where('member_id',$member_id)
                                ->update(['role'=>$add]);
        return "Success";
    }

    public function deleteMember(Request $request,$id,$member_id){

        //Remove member from tasks also

        $change = ProfileHasMember::where('project_id',$id)
                                    ->where('member_id',$member_id)
                                    ->delete();
        return "deleted";
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
        $members = DB::table('project_has_member')
        ->where('project_has_member.project_id','=',$id)
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
                        ->orderBy('id')
                        ->get();

        return $allUsers;
    }

    public function invite($id){
        $member_id = Input::get("id");
        $project = Project::find($id);

        //foreach($ids as $id){
            $user = User::find($member_id);
            SendEmails::dispatch($user,"inviteToProject",$project);
        //}
    }

}

?>