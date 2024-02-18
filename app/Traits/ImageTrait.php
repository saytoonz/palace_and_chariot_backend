<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
trait ImageTrait
{
    /**
     * @param Request $request
     * @return $this|false|string
     */

    public function uploadAvatar(Request $request, $field_name = "image", $title = "",)
    {
        $validator = Validator::make(
            $request->all(),
            [
                $field_name => ['file', 'image'],
            ],
        );

        if ($validator->fails()) return false;

        $imageImage = $title . '.png';
        $request->file($field_name)->storeAs('public/user_data/avatar', $imageImage);
        // Image::make(storage_path().'/app/public/user_data/avatar/'.$imageImage)
        // ->encode('png')->fit(100, 100, function ($constraint) {
        //     $constraint->upsize();
        // })->save();

        return  "/storage/user_data/avatar/" . $imageImage;
    }
}
