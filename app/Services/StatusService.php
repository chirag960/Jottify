<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Project;
use App\Status;
use App\User;


class StatusService{

    protected $status;

    public function __construct(Status $status){
        $this->status = $status;
    }

    public function create(Request $request, $id){
        $statuses = DB::table('status')->where('project_id',$id)->get();
        $count = count($statuses);
        
        $status = new Status;
        $status->project_id = $id;
        $status->title = $request->title;
        $status->order = $request->order;
        
        if($count == $request->order){
            $status->save();
            return $status;
        }
        else if($count > $request->order){
            $update = Status::where('project_id',$id)
                    ->where('order','>=',$request->order)
                    ->increment('order');
            $status->save();
            return $status;
        }
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
