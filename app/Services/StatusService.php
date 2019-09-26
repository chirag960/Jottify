<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Models\Task;

class StatusService{

    protected $status;

    public function __construct(Status $status){
        $this->status = $status;
    }

    public function index($id){
        return $this->status->index($id);
    }

    public function create(Request $request, $id){
        $statuses = $this->status->index($id);
        $count = count($statuses);
        $order;
        if($request->status_id == "new"){
            $order = 0;
            $beforeStatusId = -1;
        }
        else{
            $beforeStatus = Status::find($request->status_id);
            $order = $beforeStatus->order;
            $beforeStatusId = $beforeStatus->id;
            $order+=1;
        }
        
        if($count == $order){
            $status = $this->status->create($id,$request->title,$order);
        }
        else if($count > $order){
            $this->status->incrementSingleOrder($id,$order);
            $status = $this->status->create($id,$request->title,$order);
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
                $this->status->decrementOrder($project_id,$prev+1,$new);
            }
            else if($prev > $new){
                $this->status->incrementOrder($project_id,$new,$prev-1);
            }
            $this->status->setOrder($id,$new);
            return "Changes made";
    }

    public function delete($project_id,$id){
        (new Task)->deleteByStatus($id);

        $statuses = $this->status->index($project_id);
        $count = count($statuses);
    
        $status = Status::find($id);
        $order = $status->order;

        if($order+1 < $count){
            $this->status->decrementOrder($project_id,$order+1,$count-1);
        }

        $status->archiveStatus();
        return response()->json(["message"=>"success","id"=>$id,"title"=>$status->title],200);
    }
}
