<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Task;
use App\TaskHasMember;
use App\Attachment;
use App\Services\AttachmentService;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService){
        $this->attachmentService = $attachmentService;
    }

    public function create(Request $request, $project_id, $id){
        $values = $this->attachmentService->create($request, $project_id, $id);
        return $values;
    }
}
