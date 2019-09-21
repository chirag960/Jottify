<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;

class StatusService{

    protected $status;

    public function __construct(Status $status){
        $this->status = $status;
    }

    public function index($id){
      $status = Status::where('project_id',$id)->where('archived',false)->orderBy('order')->get();
      return $status;
    }

    public function create(Request $request, $id){
        $statuses = Status::where('project_id',$id)->get();
        $count = count($statuses);
        $order;
        if($request->status_id == "new"){
            $order = 0;
            $beforeStatusId = -1;
        }
        else{
            $beforeStatus = Status::where('id',$request->status_id)->first();
            $order = $beforeStatus->order;
            $beforeStatusId = $beforeStatus->id;
            $order+=1;
        }
        $status = new Status;
        $status->project_id = $id;
        $status->title = $request->title;
        $status->order = $order;
        
        if($count == $order){
            $status->save();
        }
        else if($count > $order){
            $update = Status::where('project_id',$id)
                    ->where('order','>=',$order)
                    ->increment('order');
            $status->save();
        }
        return response()->json(
            ['message'=>'success','id'=>$status->id,'beforeStatusId'=>$beforeStatusId,'title'=>$request->title,'order'=>$order]
            ,201);
    }

    public function update(Request $request, $project_id, $id){
            $prev = $request->previous_order;
            $new = $request->new_order;
            if($prev == $new){
                return "No changes made";
            }
            else if($prev < $new){
                $update = Status::where('project_id',$project_id)
                        ->whereBetween('order',array($prev+1,$new))
                        ->decrement('order');
            }
            else if($prev > $new){
                $update = Status::where('project_id',$project_id)
                        ->whereBetween('order',array($new,$prev-1))
                        ->increment('order');
            }
            $update = Status::where('id',$id)
                            ->update(['order'=>$new]); 
            return "Changes made";
    }

    public function hide($project_id,$id){
        
    }
}
