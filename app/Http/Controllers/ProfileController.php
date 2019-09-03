<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Services\ProfileService;

class ProfileController extends Controller
{

    protected $profileService;

    public function __construct(ProfileService $profileService){
        $this->profileService = $profileService;
    }

    public function update(Request $request){
        User::find(auth()->user()->id)->update(['name'=>$request->name]);
        return;
    }

    public function updateImage(Request $request){
        $image = $this->profileService->updateImage($request);
        return $image;
    }

}
