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
use Intervention\Image\ImageManagerStatic as Image;

class ProfileService{

    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function update(Request $request){
        
        User::find(auth()->user()->id)->update(['name'=>$request->name]);
        return response()->json(["message"=>"success","name"=>$request->name],200);
    }

    public function updateImage(Request $request){

        $image = $request->file('image');
        
        $date = date_create();
        $timestamp = date_timestamp_get($date);
        $name=$image->getClientOriginalName();
        $relative_path = '/media/user_profile_photo/'.$timestamp.$name;
        $path = public_path().$relative_path;
        
        $image_resize = Image::make($image->getRealPath()); 
        $min_size = ($image_resize->height() >= $image_resize->width())?$image_resize->width():$image_resize->height();
        $image_resize->crop($min_size,$min_size);
        $image_resize->save($path);

        
        $currImage = User::where('id',auth()->id())->select('photo_location')->first();
        $currLocation = public_path().$currImage->photo_location;
       
        if(($currLocation != public_path()."/media/user_profile_photo/default.png") && (File::exists($currLocation))){
            File::delete($currLocation);
        }
        

        $update = User::find(auth()->id())->update(['photo_location' => $relative_path]);
        return response()->json(['message'=>'success','location'=>$relative_path]);
    }
}

?>