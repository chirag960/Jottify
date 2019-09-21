<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use App\Services\StatusService;
use Validator;

class StatusController extends Controller
{
  protected $statusService;

  public function __construct(StatusService $statusService){
      $this->statusService = $statusService;
  }
   
    public function index($id){
      $status = $this->statusService->index($id);
      return $status;
    }

    public function create(Request $request, $id){
        $validator = Validator::make($request->all(), [
          'title' => 'required|min:3|max:30|required',
      ]);

      if($validator->fails()){
          return response()->json(array(
              'message' => "errors",
              'errors' => $validator->getMessageBag()->toArray()), 400);
      }
      else{
        $status= $this->statusService->create($request, $id);
        return $status;
      }

    }

    public function update(Request $request, $id, $status_id){
      $status= $this->statusService->update($request, $id, $status_id);
      return $status;

    }
}
