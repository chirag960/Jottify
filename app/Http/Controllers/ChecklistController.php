<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Status;
use App\Services\ChecklistService;
use Validator;

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
        $validator = Validator::make($request->all(), [
            'message' => 'required|min:3|max:30|required',
        ]);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 200);
        }
        else{
            $response = $this->checklistService->create($request,$project_id,$task_id);
            return $response;
        }
        
    }

    public function update(Request $request,$project_id,$id,$checklist_id){

        $response = $this->checklistService->update($request,$project_id,$id,$checklist_id);
        return $response;
    }

}
