<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{
    //
    function toggleFavorite(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'object_id' => ['required', 'int'],
                'app_user_id' => ['required', 'int'],
                'type' => ['required', 'max:255', 'string'],
            ],
        );

        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $fav =  Favorite::where('object_id', $request->object_id)
            ->where('app_user_id', $request->app_user_id)
            ->where('type', $request->type)->first();

        if ($fav == null) {
            $create = Favorite::create($request->all());
            if($create){
                 return response()->json([
                "error" => false,
                'msg' => "Item added to favorites successfully",
            ]);
            }else{
                return response()->json([
                    "error" => true,
                    'msg' => "Error adding item to favorites",
                ]);
            }

        } else {
            $delete = Favorite::find($fav->id)->delete();
            if($delete){
                return response()->json([
               "error" => false,
               'msg' => "Item removed from favorites successfully",
           ]);
           }else{
               return response()->json([
                   "error" => true,
                   'msg' => "Error removing item from favorites",
               ]);
           }
        }
    }
}
