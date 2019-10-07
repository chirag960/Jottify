<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Comment;
use Validator;
use Carbon\Carbon;

class CommentService{

    protected $comment;

    public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    public function index($project_id,$id){

        return $this->comment->index($id);
    }

    public function create(Request $request, $project_id, $id){
        $comment = (new Comment)->create($id, $request->comment);
        (new Task)->incrementCommentCount($id);
        
        return response()->json(["message"=>"success","comment"=>$comment],201);
    }

    public function delete($project_id, $id, $comment_id){
        (new Task)->decrementCommentCount($id);
        Comment::destroy($comment_id);
        return response()->json(["message"=>"success","id"=>$comment_id],200);
    }
}

?>