<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\User;
use App\Services\CommentService;
use Validator;

class CommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService){
        $this->commentService = $commentService;
    }

    public function index($project_id, $id){
        $values = $this->commentService->index($project_id,$id);
        return $values;
    }

    public function create(Request $request,$project_id, $id){

        $request->comment = strip_tags($request->comment);

        $validator = Validator::make($request->all(), [
            'comment' => 'required|max:255',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 400);
        }
        else{
            $values = $this->commentService->create($request,$project_id, $id);
            return $values;
        }
        
    }
}
