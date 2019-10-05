<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ProfileService;
use Validator;

class ProfileController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService){
        $this->profileService = $profileService;
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:20']);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 200);
        }
        else{
            $response = $this->profileService->update($request);
            return $response;
        }
    }

    public function updateImage(Request $request){
        
        $validator = Validator::make($request->all(),[
            'image' => 'required|mimetypes:image/*|max:2048'
        ]);

        if($validator->fails()){
            return response()->json(array(
                'message' => "errors",
                'errors' => $validator->getMessageBag()->toArray()), 200);
        }
        else{
            $image = $this->profileService->updateImage($request);
            return $image;
        }
        
    }
}
