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
use App\Models\Task;
use App\Models\TaskHasMember;

class ProjectService{

    protected $project;

    public function __construct(Project $project){
        $this->project = $project;
    }

    public function index(){
        
        $id = auth()->id();
        //$projects = new ProjectHasMember;
        return (new ProjectHasMember)->getProjects($id);
    
    }

    public function create(Request $request){

        $project = $this->project->create($request->title,$request->description);

        $project_id = $project->id;
        $background = $project->background;

        $projectHasMember = (new ProjectHasMember)->create($project_id,auth()->id(),2);
        $projectHasMember->save();

        $statuses = new Status;
        $statuses->createDefaultStatuses($project_id);

        return response()->json(["message"=>"success","id"=>$project_id,"title"=>$project->title,"background"=>$project->background],201);

    }

    public function getProjectDetails($id){
        $project = Project::find($id);
        $projectHasMember = new ProjectHasMember;
        $roleAndStar = $projectHasMember->getRoleAndStar($id);
        $members = $projectHasMember->getMembersDetails($id);

        $date = new Carbon($project->created_at);
        $format_date = $date->toFormattedDateString();

        $response = array();
        $response =[
            "id" => $project->id,
            "title" => $project->title,
            "description" => $project->description,
            "created_at" => $format_date,
            "background" => $project->background,
            "star" => $roleAndStar->star,
            "role" =>$roleAndStar->role,
            "members" => $members ];

        return $response;

    }

    public function delete($id){
        (new Project)->deleteProject($id);
        return response()->json(["message"=>"success","id"=>$id],200);
    }

    public function updateStar(Request $request, $id){
        $change = ($request->star=="1")?true:false;
        (new ProjectHasMember)->setStar($id,$change);
        return response()->json(["message"=>"success","star"=>$request->star,"id"=>$id],200);
    }

    public function updateMember(Request $request,$id,$member_id){
        if($request->admin == 1){
            $add = true;
        }   
        else{
            $add = false;
        }
        (new ProjectHasMember)->setAdmin($id,$member_id,$add);
        return response()->json(['message'=>'success','id'=>$member_id],200);

    }

    public function deleteMember($id,$member_id){
        $task_ids = Task::where('project_id',$id)->pluck('id')->toArray();
        (new TaskHasMember)->deleteMember($task_ids,$member_id);
        (new ProjectHasMember)->deleteMember($id,$member_id);
        return response()->json(['message'=>'success','id'=>$member_id],200);
    }


    public function generateReport($id){
        $data = ['title' => 'A pdf is generated'];
        $pdf = PDF::loadView('reportPDF', $data);
        return $pdf;
    }

    public function allUsers($id){
        $pattern = Input::get('pattern');
        $project_member_ids = (new ProjectHasMember)->getMemberIds($id);
        $allUsers = (new User)->searchUser($pattern, $project_member_ids);
        return response()->json(["message"=>"success","members"=>$allUsers],200);
    }

    public function invite(Request $request, $id){
        $ids = $request->membersList;
        $message = $request->inviteMessage;
        $project = Project::find($id);
        $project->message = $message;

        for($i = 0; $i <count($ids); $i++){
            $inserts[] = [
                'project_id' => $id,
                'member_id' => $ids[$i]
            ];
        }
        if(isset($inserts)){
            ProjectHasMember::insert($inserts);
            SendEmails::dispatch($ids,"inviteToProject",$project);
        }

        $members = (new User)->getUserDetails($ids);

        return response()->json(["message"=>"success","members"=>$members]);
    }

}

?>