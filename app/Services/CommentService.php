<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;
use App\Models\Comment;
use Validator;

class CommentService{

    protected $comment;

    public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    public function index($project_id,$id){
        //$comments = Comment::where('task_id',$id)->user;

        $comments = DB::table('comments')
                    ->rightJoin('users','comments.user_id','=','users.id')
                    ->where('comments.task_id','=',$id)
                    ->select('comments.id','comments.task_id','comments.user_id','users.name','comments.message','comments.created_at')
                    ->orderBy('comments.created_at','DESC')
                    ->get();

        return $comments;
    }

    public function create(Request $request, $project_id, $id){
        $comment = new Comment;
        $comment->task_id = $id;
        $comment->user_id = auth()->id();
        $comment->message = $request->comment;
        $comment->save();
        return response()->json(["message"=>"success","comment"=>$comment],201);
    }
}

?>