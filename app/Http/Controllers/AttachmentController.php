<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\TaskHasMember;
use App\Models\Attachment;
use App\Services\AttachmentService;
use Validator;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService){
        $this->attachmentService = $attachmentService;
    }

    public function index($project_id, $id){
        $attachments = $this->attachmentService->index($project_id, $id);
        return $attachments;
    }

    public function create(Request $request, $project_id, $id){
        dd($request->files);
        $validator = Validator::make($request->all(),[
            "files.*" => "required|mimetypes:image/*,application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document/,application/rtf|max:2048"
        ]);
        
        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 400);
        }else{
            $attachment_count  =Task::where('id',$id)->first()->attachment_count;
            $f = $request->file('files');
            if(count($f) == 0){
                return response()->json(array(
                    'message' => "count-error",
                    'errors' => "upload a "),400);
            }
            else if((count($f) + $attachment_count) > 10){
                return response()->json(array(
                    'message' => "count-error",
                    'errors' => "each task can have only 10 attachments"),400);
            }else{
                $values = $this->attachmentService->create($request, $project_id, $id);
                return $values;
            }
        }
        
    }

    public function delete($project_id, $id, $attachment_id){
        $response = $this->attachmentService->delete($project_id, $id, $attachment_id);
        return $response;
    }
}
