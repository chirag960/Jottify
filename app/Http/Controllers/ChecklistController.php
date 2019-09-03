<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Facades\DB;
use App\Status;
use App\Services\ChecklistService;

class ChecklistController extends Controller
{

    protected $checklistService;

    public function __construct(ChecklistService $checklistService){
        $this->checklistService = $checklistService;
    }
    
    public function index($project_id,$id){
        $checklist = $this->checklistService->index($project_id,$id);
        return $checklist;
    }

    public function create(Request $request,$project_id,$task_id){

        $response = $this->checklistService->create($request,$project_id,$task_id);
        return $response;
    }

    public function update(Request $request,$project_id,$task_id,$id){

        $response = $this->checklistService->update($request,$project_id,$task_id,$id);
        return $response;
    }

}
