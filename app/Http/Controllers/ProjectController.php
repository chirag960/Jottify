<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectHasMember;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\InviteMail;
use Redirect;
use Validator;
use Carbon\Carbon;

class ProjectController extends Controller
{

    protected $projectService;

    public function __construct(ProjectService $projectService){
        $this->projectService = $projectService;
    }

    public function index(){
        $projects = $this->projectService->index();
        return $projects;
    }

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:30|required',
            'description' => 'sometimes|max:255',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 200);
        }
        else{
            $response = $this->projectService->create($request);
            return $response;
        }
    }

    public function getProjectDetails($id,$message="none"){
        $project = $this->projectService->getProjectDetails($id);
        if($message="none"){
            return view('project')->with('project',$project);
        }
        else{
            return view('project')->with(['project'=>$project,'message'=>$message]);
        }
        
    }

    public function update(Request $request, $id){

        $project = $this->projectService->update($request, $id);
        return "Updated successfully";
    }

    public function delete($id){
        $response = $this->projectService->delete($id);
        return $response;
    }

    public function updateImage(){
        
    }

    public function updateStar(Request $request,$id){
        $project = $this->projectService->updateStar($request, $id);
        return $project;
    }

    public function updateTitle(Request $request,$id){
        $project = $this->projectService->updateTitle($request, $id);
        return $project;
    }

    public function updateDescription(Request $request,$id){
        $project = $this->projectService->updateDescription($request, $id);
        return $project;
    }

    public function updateMember(Request $request,$id,$member_id){
        $project = $this->projectService->updateMember($request, $id, $member_id);
        return $project;
    }

    public function deleteMember($id,$member_id){
        $project = $this->projectService->deleteMember($id, $member_id);
        return $project;
    }

    public function generateReport($id){
        $pdf = $this->projectService->generateReport($id);
        return $pdf->download('project'.$id.'.pdf');
    }

    public function allUsers($id){
        $allUsers = $this->projectService->allUsers($id);
        return $allUsers;
    }

    public function invite(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'membersList' => 'required',
            'inviteMessage' => 'required|max:255',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 200);
        }
        else{
        $response = $this->projectService->invite($request, $id);
        return $response;
        }
    }

    // public function memberDetails($id, $member_id){

    //     $member = ProjectHasMember::where('project_id',$id)->where('member_id',$member_id)->first();
    //     if(isset($member)){
    //         $member = $this->projectService->memberDetails($id, $member_id);
    //         return $member;
    //     }
    //     else{
    //         return response()->json(["message"=>"The user is not in this project"],200);
    //     }
        
        
    // }

}
