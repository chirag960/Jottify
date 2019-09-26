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
        $attachments = Task::find($id)->attachments()->orderBy('created_at')->get();    
        return $attachments;
    }

    public function create(Request $request,$project_id, $id){
        // $id is task id
        
        $f = $request->file('files');
        $str = '';
        $attachments = array();
        foreach($f as $file){
                $timestamp = Carbon::now()->timestamp;
                
                $name=$file->getClientOriginalName();
                $relative_name = $timestamp.$name;

                $relative_path = '/media/task_attachments/'.$timestamp.$name;
                $folder_path = public_path().'/media/task_attachments/';

                $file->move($folder_path,$relative_name);
                
                $type = $this->getType($name);
                $attachment = (new Attachment)->create($id,$name,$type,$relative_path);

                array_push($attachments, $attachment);
            }
            (new Task)->incrementAttachmentCount($id,count($attachments));
        
        return response()->json(["message"=>"success", "attachments"=>$attachments],201);

    }

    public function delete($project_id, $id, $attachment_id){
        $name = Attachment::find($attachment_id)->name;
        File::delete(public_path().$name);
        (new Task)->decrementAttachmentCount($id);
        Attachment::destroy($attachment_id);
        return response()->json(["message"=>"success","id"=>$attachment_id],200);
    }

    public function getType($name){
        $extension = substr(strrchr($name,'.'),1);
        if($extension =="jpg" || $extension =="png" || $extension =="jpeg"){
            $type = "image";
        }
        else{
            $type = "document";
        }
        return $type;
    }
}

?>