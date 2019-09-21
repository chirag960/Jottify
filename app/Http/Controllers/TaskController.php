<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\TaskHasMember;
use App\Services\TaskService;
use App\Models\User;
use App\Models\Status;
use App\Jobs\SendEmails;
use Illuminate\Support\Facades\Input;
use Validator;
use Carbon\Carbon;

class TaskController extends Controller
{
    
    protected $taskService;

    public function __construct(TaskService $taskService){
        $this->taskService = $taskService;
    }

    public function index($id){
        $tasks = $this->taskService->index($id);
        return $tasks;
    }

    public function create(Request $request, $id){
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
            $values = $this->taskService->create($request, $id);
            return $values;
        }
    }

    public function delete($project_id,$id){
        $response = $this->taskService->delete($project_id, $id);
        return $response;
    }

    public function getTaskDetails($project_id,$id){
        $task = $this->taskService->getTaskDetails($project_id,$id);
        return view('task')->with('task',$task);
    }

    public function getTaskMembers($project_id,$id){
        $members = $this->taskService->getTaskMembers($project_id,$id);
        return $members;
    }

    public function updateduedate(Request $request, $project_id,$id){
        $validator = Validator::make($request->all(), [
            'date' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 400);
        }
        else{
            $timestamp = new Carbon($request->date);
            $now = new Carbon();
            $after_two_years = (new Carbon())->addYears(2);
            if($timestamp == false){
                return response()->json(array(
                    'message' => "date-error",
                    'errors' => "date format is not correct"), 400);
            }
            else if($timestamp > $after_two_years || $timestamp < $now){
                return response()->json(array(
                    'message' => "date-error",
                    'errors' => "date should be within 2 years from today"), 400);
            }
            else{
                $date = $this->taskService->updateduedate($request,$project_id,$id);
                return $date;
            }
        }
        
    }

    public function updateDescription(Request $request, $project_id,$id){
        $validator = Validator::make($request->all(), [
            'description' => 'required|max:2048',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 400);
        }
        else{
            $desc = $this->taskService->updateDescription($request,$project_id,$id);
            return $desc;
        }
        
   }

   public function updateStatus(Request $request, $project_id,$id){
        $status = Status::where('project_id',$project_id)->where('id',$request->status_id)->first();
        if(!isset($status)){
            return response()->json(array(
                'message' => "status-error",
                'errors' => "No such status found"), 400);
        }
        else{
            $status = $this->taskService->updateStatus($request,$project_id,$id);
            return $status;
        }
        
    }

    public function assignTask(Request $request, $project_id,$id){
        $status = $this->taskService->assignTask($request,$project_id,$id);
        return $status;
    }

    public function titlesList(){
        $titleList = $this->taskService->titlesList();
        return $titleList;
    }

}
