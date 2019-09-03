<?php

namespace App\Services;

use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\ProjectHasMember;
use App\Project;
use App\Status;
use App\User;
use App\Jobs\SendEmails;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileService{

    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function updateImage(Request $request){
        $image = $request->file('image');
        
        //$fileName = $image->getClientOriginalName();
        
        $date = date_create();
        $timestamp = date_timestamp_get($date);
        $name=$image->getClientOriginalName();
        $relative_path = '/media/user_profile_photo/'.$timestamp.$name;
        $path = public_path().$relative_path;
        
        //$image->move(public_path().'/media/user_profile_photo/',$timestamp.$name);

        $image_resize = Image::make($image->getRealPath()); 
        $min_size = ($image_resize->height() >= $image_resize->width())?$image_resize->width():$image_resize->height();
        $image_resize->crop($min_size,$min_size);
        $image_resize->save($path);

        /*
        $currImage = User::where('id',auth()->id())->select('photo_location')->first();
        $currLocation = $currImage->photo_location;
        dd(File::exists($currLocation)." ".$currLocation);
        if(($currLocation != "//media//user_profile_photo//default.png") && (File::exists($currLocation))){
            dd("inside delete func");
            File::delete($currLocation);
        }
        */

        $update = User::find(auth()->id())->update(['photo_location' => $relative_path]);
        return $relative_path;
    }
}

?>