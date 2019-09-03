<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Task;
use App\Comment;

class CommentService{

    protected $comment;

    public function __construct(Comment $comment){
        $this->comment = $comment;
    }

    public function index($project_id,$id){
        //$comments = Comment::where('task_id',$id)->user;

        $comments = DB::table('comment')
                    ->rightJoin('users','comment.user_id','=','users.id')
                    ->where('comment.task_id','=',$id)
                    ->select('comment.id','comment.task_id','comment.user_id','users.name','comment.message','comment.edited','comment.timestamp')
                    ->orderBy('comment.timestamp','DESC')
                    ->get();

        return $comments;
    }

    public function create(Request $request, $project_id, $id){
        $comment = new Comment;
        $comment->task_id = $id;
        $comment->user_id = auth()->id();
        $comment->message = $request->message;
        $comment->edited = "0";
        $comment->save();
        return $comment;
    }
}

?>