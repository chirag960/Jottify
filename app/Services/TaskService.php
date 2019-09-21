<?php

namespace App\Services;


use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectHasMember;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskHasMember;
use App\Models\TaskDetails;
use App\Jobs\SendEmails;
use Carbon\Carbon;

class TaskService{

    protected $task;

    public function __construct(Task $task){
        $this->task = $task;
    }

    public function index($id){
        $tasks = Task::where('project_id','=',$id)->orderBy('status_id')->get();
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
        $taskHasMember->save();

        $task_details = new TaskDetails;
        $task_details->task_id = $task->id;
        $task_details->status_id = $request->status_id;
        $task_details->save();

        return response()->json(
            ['message'=>'success','id' => $task->id,'title'=> $request->title, 'status_id'=> $request->status_id]
        ,201);

    }

    public function delete($project_id,$id){
        $task = Task::find($id) ;
        $task->delete();
        return response()->json(["message"=>"success","id"=>$id],200);
    }

    public function getTaskDetails($project_id,$id){
        $task = Task::find($id);
        if(isset($task->due_date)){
            $date = new Carbon($task->due_date);
            $task->due_date = $date->toFormattedDateString();
        }
        $role = ProjectHasMember::where('project_id',$project_id)->where('member_id',auth()->id())->first()->role;
        $task->role = $role;
        return $task;
    }

    public function getTaskMembers($project_id,$id){
        $projectMembers = DB::table('project_has_members')
                    ->rightJoin('users','project_has_members.member_id','=','users.id')
                    ->where('project_has_members.project_id','=',$project_id)
                    ->select('users.id','users.name','users.email','project_has_members.role','users.photo_location')
                    ->get();
        //dd($projectMembers);
        $members = TaskHasMember::where('task_id',$id)->pluck('member_id')->toArray();
        //dd($members);
        foreach($projectMembers as $projectMember){
            if(in_array($projectMember->id, $members) ){
                $projectMember->present = true;
            }else{
                $projectMember->present = false;
            }
        }

        return $projectMembers;
    }

    public function updateduedate(Request $request,$project_id,$id){
       
        $format = "M j, Y";
        $timestamp = DateTime::createFromFormat($format,$request->date);

        $date = Task::find($id)->update(['due_date'=>$timestamp]);
        return response()->json(['message'=>'success','date'=>$request->date]);
    }

    public function updateDescription(Request $request,$project_id,$id){ 
            $query = Task::find($id)->update(['description'=>$request->description]);
            return response()->json(["message"=>"success","description"=>$request->description],200);
    }
    
    public function updateStatus(Request $request,$project_id,$id){
        
        $status = Task::find($id)->update(['status_id'=>$request->status_id]);
        $details = new TaskDetails;
        $details->task_id = $id;
        $details->status_id = $request->status_id;
        $details->save();
        return response()->json(['message'=>'success','status_id'=>$request->status_id],200);
    }

    public function assignTask(Request $request,$project_id,$id){
        $task = Task::find($id);

        $present_members = TaskHasMember::where('task_id',$id)->pluck('member_id')->toArray();
        $request_members = array_map('intval', $request->ids);
        $new_members = array_values(array_diff($request_members,$present_members));
        $remove_members = array_values(array_diff($present_members,$request_members));
        
        for($i = 0; $i <count($new_members); $i++){
            $inserts[] = [
                'task_id' => $id,
                'member_id' => $new_members[$i]
            ];
        }
        if(isset($inserts)){
            TaskHasMember::insert($inserts);
            //SendEmails::dispatch($user,"assignToTask",$task);
        }
        if(isset($remove_members)){
            $delete = TaskHasMember::where('task_id',$id)->whereIn('member_id',$remove_members)->delete();
        }
        
        return response()->json(["message"=>"success","members"=>$request_members],200);
    }

    public function titlesList(){

        $pattern = Input::get('pattern');

        $id = auth()->id();
        $projects = DB::table('project_has_members')
                    ->rightJoin('projects','project_has_members.project_id','=','projects.id')
                    ->where('project_has_members.member_id','=',auth()->id())
                    ->where('projects.title','like','%'.$pattern.'%')
                    ->select(['id','title'])
                    ->get();

        $tasks = DB::table('task_has_members')
                ->rightJoin('tasks','task_has_members.task_id','=','tasks.id')
                ->where('task_has_members.member_id','=',auth()->id())
                ->where('tasks.title','like','%'.$pattern.'%')
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