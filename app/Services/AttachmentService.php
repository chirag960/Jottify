<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Status;
use App\Task;
use App\Attachment;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class AttachmentService{

    protected $attachment;

    public function __construct(Attachment $attachment){
        $this->attachment = $attachment;
    }

    public function index($id){
        $attachments = DB::table('task')->where('task_id','=',$id)->orderBy('timestamp')->get();    
        return $attachments;
    }

    public function create(Request $request,$project_id, $id){
        // $id is project id
        
        $validator = Validator::make($request->all(),[
            "files.*" => "required|mimetypes:image/*,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document/,application/rtf|max:5120"
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => $validation->errors()->all(),
                'uploaded_image' => '',
                'class_name'  => 'alert-danger'
               ]);
       }

        $f = $request->file('files');
        $str = '';
        $files = array();
        foreach($f as $file){
                $date = date_create();
                $timestamp = date_timestamp_get($date);
                $str = $str.'1';
                $name=$file->getClientOriginalName();
                $path = public_path().'/media/task_attachments/'.$timestamp.$name;
                $file->move(public_path().'/media/task_attachments/',$timestamp.$name);
                $attachment = new Attachment;
                $attachment->task_id = $id;
                //$attachment->type = $id;  set type
                $attachment->location = $path;
                //dd(mime_content_type($path));
        }
        
        return "Success";
        
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
}

?>