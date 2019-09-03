<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Status;
use Illuminate\Support\Facades\DB;
use App\Services\StatusService;

class StatusController extends Controller
{
  protected $statusService;

  public function __construct(StatusService $statusService){
      $this->statusService = $statusService;
  }
   
    public function index($id){
      $status = DB::table('status')->where('project_id',$id)->where('delete',false)->get();
      return $status;
    }

    public function create(Request $request, $id){
      $status= $this->statusService->create($request, $id);
      return $status;

    }

    public function update(Request $request, $id, $status_id){
      $status= $this->statusService->update($request, $id, $status_id);
      return $status;

    }
}
