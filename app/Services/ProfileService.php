<?php

namespace App\Services;

use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProjectHasMember;
use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use App\Jobs\SendEmails;
use App\Utils\ImageUtils;
use Carbon\Carbon;

class ProfileService{

    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function update(Request $request){
        (new User)->setName($request->name);
        return response()->json(["message"=>"success","name"=>$request->name],200);
    }

    public function updateImage(Request $request){

        $image = $request->file('image');
        
        $timestamp = Carbon::now()->timestamp;
        $name=$image->getClientOriginalName();
        $relative_path = '/media/user_profile_photo/'.$timestamp.$name;
        $path = public_path().$relative_path;
        
        $square_image = (new ImageUtils)->squareCut($image);
        $square_image->save($path);
        
        $this->deletePreviousProfileImage();
        
        (new User)->setProfileImage($relative_path);
        return response()->json(['message'=>'success','location'=>$relative_path]);
    }

        public function deletePreviousProfileImage(){
            $currImage = User::where('id',auth()->id())->select('photo_location')->first();
            $currLocation = public_path().$currImage->photo_location;
           
            if(($currLocation != public_path()."/media/user_profile_photo/default.png") && (File::exists($currLocation))){
                File::delete($currLocation);
            }    
        }
}

?>