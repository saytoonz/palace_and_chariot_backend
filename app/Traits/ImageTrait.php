<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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
                $field_name => ['required', 'image',  'max:2048',],
            ],
        );

        if ($validator->fails()) return false;

        $imageImage = $title . '.png';
        $request->file($field_name)->storeAs('public/', $imageImage);
        // Image::make(storage_path().'/app/public//avatar/'.$imageImage)
        // ->encode('png')->fit(100, 100, function ($constraint) {
        //     $constraint->upsize();
        // })->save();

        return  '/storage/' . $imageImage;
    }


}
