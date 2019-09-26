<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Project;
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

    public function getOnlyTaskMembers($project_id,$id){
        $members = $this->taskService->getOnlyTaskMembers($project_id,$id);
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

    public function report($id){
        $tasks = Project::find($id)->tasks;
        
        $timestamp = Carbon::now()->timestamp;
        $data = $tasks->toArray();
        
        $title = Project::find($id)->title;
        $file_name = $title.$timestamp;
        $out = fopen($file_name.'.csv', 'w');
        $csv_headers = array("title","Members","Created at", "Updated at","Due Date","Attachment Count","Current Status","Progress");
        fputcsv($out, $csv_headers);

        foreach($tasks as $task){

            $dt = new Carbon($task->created_at);
            $dt = $dt->toDayDateTimeString();
            $task->creation_date = $dt;

            $dt = new Carbon($task->updated_at);
            $dt = $dt->toDayDateTimeString();
            $task->last_updated_date = $dt;

            $task->current_status = Status::find($task->status_id)->title;

            $date = new Carbon($task->due_date);
            $task->due_date = $date->toFormattedDateString();

            if(is_null($task->checklist_item_count)){
                $task->progress = "NA";
            }
            else if($task->checklist_item_count == 0){
                $task->progress = "0%";
            }
            else{
                $progress = ceil(($task->checklist_done/$task->checklist_item_count)*100);
                $task->progress = $progress."%";
            }
            $members = $task->users()->pluck('name')->toArray();
            if(count($members) == 0){
                $members = "NA";
            }
            else{
                $members = implode(', ', $members);
            }
            $task->members = $members;

            $line = array($task->title,$task->members,$task->creation_date,$task->last_updated_date,$task->due_date,$task->attachment_count,
                        $task->current_status,$task->progress);
            
            fputcsv($out, $line);

        }

        fclose($out);

        $headers = array(
            'Content-Type'=> 'text/csv',
            'Content-Disposition'=> 'attachment',
            'filename'=>$file_name
        );

        return response()->download(public_path().'/'.$file_name.'.csv',$file_name.'.csv', $headers)->deleteFileAfterSend();;


    }

}
