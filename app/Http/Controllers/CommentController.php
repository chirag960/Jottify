<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Task;
use App\User;
use App\Services\CommentService;

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
        $values = $this->commentService->create($request,$project_id, $id);
        return $values;
    }
}
