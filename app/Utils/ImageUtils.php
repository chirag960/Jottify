<?php

namespace App\Utils;

use Intervention\Image\ImageManagerStatic as Image;

class ImageUtils{

    public function squareCut($image){
        $image_resize = Image::make($image->getRealPath()); 
        $min_size = ($image_resize->height() >= $image_resize->width())?$image_resize->width():$image_resize->height();
        $image_resize->crop($min_size,$min_size);
        return $image_resize;
    }

}

?>