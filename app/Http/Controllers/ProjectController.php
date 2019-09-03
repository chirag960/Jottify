<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\ProjectHasMember;
use Illuminate\Support\Facades\DB;
use App\Status;
use App\Services\ProjectService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\InviteMail;
use Redirect;

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
        $project_id = $this->projectService->create($request);
        //return redirect()->action('ProjectController@sendMail',['email' => Auth::user()->email]);
        return response()->redirectToAction('ProjectController@getProjectDetails',['id' => $project_id]);

    }

    public function getProjectDetails($id){
        $project = $this->projectService->getProjectDetails($id);
        return view('project')->with('project',$project);
    }

    public function sendMail($email){
        $comment = 'HI! testing email. The project is created.';
        Mail::to($email)->send(new InviteMail($comment));
        return "Email sent";
    }

    public function update(Request $request, $id){

        $project = $this->projectService->update($request, $id);
        return "Updated successfully";

    }

    public function delete(Request $request, $id){
        $project = $this->projectService->delete($request ,$id);
        return "Deleted successfully";
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
        $project = $this->projectService->updateMember($request, $id);
        return $project;
    }

    public function deleteMember(Request $request,$id,$member_id){
        $project = $this->projectService->deleteMember($request, $id);
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

    /*
    public function create(Request $request,$id){
        $ids = $request->members;

        foreach($ids as $id){
            $user = User::find($id);
            SendEmails::dispatch($user,"inviteToProject");
        }
    }
    */

    public function invite($id){
        $this->projectService->invite($id);
        //return $allUsers;
    }

}
