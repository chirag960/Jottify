<?php

namespace App\Services;

use File;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Models\Task;
use App\Models\Attachment;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AttachmentService{

    protected $attachment;

    public function __construct(Attachment $attachment){
        $this->attachment = $attachment;
    }

    public function index($project_id,$id){
        $attachments = DB::table('attachments')->where('task_id','=',$id)->orderBy('created_at')->get();    
        return $attachments;
    }

    public function create(Request $request,$project_id, $id){
        // $id is project id
        
        $f = $request->file('files');
        $str = '';
        $attachments = array();
        foreach($f as $file){
                $date = date_create();
                $timestamp = date_timestamp_get($date);
                
                $name=$file->getClientOriginalName();
                $extension = substr(strrchr($name,'.'),1);
                if($extension =="jpg" || $extension =="png" || $extension =="jpeg"){
                    $type = "image";
                }
                else{
                    $type = "document";
                }
                $relative_path = '/media/task_attachments/'.$timestamp.$name;
                $path = public_path().$relative_path;
                $file->move(public_path().'/media/task_attachments/',$timestamp.$name);
                $attachment = new Attachment;
                $attachment->task_id = $id;
                $attachment->user_id = auth()->id();
                $attachment->name = $name;
                $attachment->type = $type;
                $attachment->location = $relative_path;
                $attachment->save();
                array_push($attachments, $attachment);
            }
            $task = Task::where('id',$id)->increment('attachment_count',count($attachments));
        
        return response()->json(["message"=>"success", "attachments"=>$attachments],201);
        
        // $file = new Attachment;
        // $file->filename = json_encode($data);
        // $file->save;

        // $image = $request->file('fileName');
        // $new_name = rand().'.'.$image->getClientOriginalName();
        // $image->move(public_path('images'), $new_name);
        // return "Succcess";

        /*Insert your data*/

        // Attachment::insert( [
        //     'images'=>  implode("|",$files),
        //     'description' =>$input['description'],
        //     //you can put other insertion here
        // ]);

        //return "Not went inside if";

    }

    public function delete($project_id, $id, $attachment_id){
        $name = Attachment::find($attachment_id)->name;
        $currLocation = public_path().$name;
        File::delete($currLocation);
        //$delete = Attachment::find($attachment_id)->delete();
        Attachment::destroy($attachment_id);
        return response()->json(["message"=>"success","id"=>$attachment_id],200);
    }
}

?>