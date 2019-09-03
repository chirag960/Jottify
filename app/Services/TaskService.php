<?php

namespace App\Services;

use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\ProjectHasMember;
use App\Project;
use App\Status;
use App\Task;
use App\User;
use App\TaskHasMember;
use App\Jobs\SendEmails;

class TaskService{

    protected $task;

    public function __construct(Task $task){
        $this->task = $task;
    }

    public function index($id){
        $tasks = DB::table('task')->where('project_id','=',$id)->orderBy('status_id')->get();
        foreach($tasks as $task){
            $members = Task::find($task->id)->users;
            if($members != "[]"){
                $task->members = $members;
            }
            
        }
        return $tasks;
    }

    public function create(Request $request, $id){
        // $id is project id
        $task = new Task;
        $task->status_id = $request->status_id;
        $task->project_id = $id;
        $task->title = $request->title;
        if(isset($request->description)){
            $task->description = $request->description;
        }
        if(isset($request->due_date)){
            $task->due_date = $request->due_date;
        }
        $task->save();

        $task_details = DB::table('task_details')->insert(['task_id'=> $task->id, 'status_id'=> $request->status_id]);

        $taskHasMember = new TaskHasMember;
        $taskHasMember->task_id = $task->id;
        $taskHasMember->member_id = auth()->id();
        $taskHasMember->role = true;
        $taskHasMember->save();

        $response = ['project_id' => $id, 'id' => $task->id];

        return $response;

    }

    public function getTaskDetails($project_id,$id){
        $task = Task::find($id);
        return $task;
    }

    public function getTaskMembers($project_id,$id){

        $check = User::find(auth()->id())->tasks->where('id','=',$id)->first();
        if($check === null){
            return "404 error, You are not assigned that task";
        }
        $members = Task::find($id)->users;
        return $members;
    }

    public function createduedate(Request $request,$project_id,$id){
        // $message = trim($request->date_input);
        // $timedate = explode(" ",'$request->date_input ');
        // $date = explode("-",'$timedate[0]  ');
        // $time = explode(":",'$timedate[1]  ');
        // $completeDate = mktime($time[2],$time[1],$time[0],$date[2],$date[1],$date[0]);
        $format = "Y/m/d H:i:s";
        $timestamp = DateTime::createFromFormat($format,$request->date);
        //dd($timestamp);
        //$mysqldate = date("Y-m-d h:i:s",$timestamp);

        $date = Task::find($id)->update(['due_date'=>$request->date]);
        return $request->date;
    }

    public function createDescription(Request $request,$project_id,$id){
        
            $date = Task::find($id)->update(['description'=>$request->message]);
            return $request->message;
    }
    
    public function updateStatus(Request $request,$project_id,$id){
        
        $status = Task::find($id)->update(['status_id'=>$request->id]);
        return $request->id;
    }

    public function assignTask(Request $request,$project_id,$id){
        $task = Task::find($id);
        foreach($request->ids as $uid){
            $user = User::find($uid);
            SendEmails::dispatch($user,"assignToTask",$task);
        }
    }

    public function titlesList(){

        $pattern = Input::get('pattern');

        $id = auth()->id();
        $projects = DB::table('project_has_member')
                    ->rightJoin('project','project_has_member.project_id','=','project.id')
                    ->where('project_has_member.member_id','=',auth()->id())
                    ->where('project.title','like','%'.$pattern.'%')
                    ->select(['id','title'])
                    ->get();

        $tasks = DB::table('task_has_member')
                ->rightJoin('task','task_has_member.task_id','=','task.id')
                ->where('task_has_member.member_id','=',auth()->id())
                ->where('task.title','like','%'.$pattern.'%')
                ->select(['id','project_id','title'])
                ->get();

        if(count($tasks)){
            foreach($tasks as $task){
                $project_title = Project::where('id',$task->project_id)->select('title')->first();
                //dd($project_title['title']);
                $task->project_title = $project_title['title'];
            }
        }

        $response = array();
        if(count($projects)){
        $response['projects'] = $projects;
        }
        if(count($tasks)){
        $response['tasks'] = $tasks;
        }
        return $response;
    }

}

?>