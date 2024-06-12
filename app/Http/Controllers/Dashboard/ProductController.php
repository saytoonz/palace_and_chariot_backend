<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccommodationSaleResource;
use App\Http\Resources\ApartmentRentResource;
use App\Http\Resources\EventServiceRentResource;
use App\Http\Resources\HotelRentResource;
use App\Http\Resources\SecurityResource;
use App\Http\Resources\TourismResource;
use App\Http\Resources\VehicleRentResource;
use App\Http\Resources\VehicleSaleResource;
use App\Models\AccommodationSale;
use App\Models\ApartmentRent;
use App\Models\EventServiceRent;
use App\Models\HotelRent;
use App\Models\Image;
use App\Models\Security;
use App\Models\Tourism;
use App\Models\VehicleKeys;
use App\Models\VehicleRent;
use App\Models\VehicleSale;
use App\Models\VehicleTextKey;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use ImageTrait;

    function getProducts(Request $request)
    {
        $vRent = NULL;
        $vSale = NULL;
        $tour = NULL;
        $security = NULL;
        $hotel = NULL;
        $events = NULL;
        $apartment = NULL;
        $accomodation = NULL;

        //   if($request->object_type == 'rent_vehicle'){
        $vRent =  VehicleRentResource::collection(VehicleRent::with(['make'])->get());

        //sale_vehicle
        $vSale =  VehicleSaleResource::collection(VehicleSale::with(['make'])->get());

        //tour
        $tour =  TourismResource::collection(Tourism::get());

        //security
        $security =  SecurityResource::collection(Security::get());

        //rent_hotel
        $hotel =  HotelRentResource::collection(HotelRent::get());

        //rent_event
        $events =  EventServiceRentResource::collection(EventServiceRent::get());

        //rent_apartment
        $apartment =  ApartmentRentResource::collection(ApartmentRent::get());

        //sale_accomm
        $accomodation =  AccommodationSaleResource::collection(AccommodationSale::get());




        $all = collect()->merge($vRent)
            ->merge($vSale)
            //   ->merge($travel)
            ->merge($tour)
            ->merge($security)
            ->merge($hotel)
            ->merge($events)
            ->merge($apartment)
            ->merge($accomodation);

        return $all->sortBy('created_at')->values();
    }

    function uploadProductImge(Request $request)
    {
        try {
            if (!$request->image) return response()->json([
                'error' => true,
                'msg' => "Image not found.",
            ]);

            $avatar = $this->uploadAvatar($request, 'image', 'image_' . date('Y-m-d H:i:s'));

            if ($avatar) {
                return response()->json([
                    'error' => false,
                    'msg' => "success",
                    'data' => $avatar,
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'msg' => "And error occurred",
                ]);
            }
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'error' => true,
                'msg' => "And error occurred",
            ]);
        }
    }

    function saveImageList($imageList, $objectId, $objectType)
    {

        $imagePaths = explode(',', $imageList);

        foreach ($imagePaths as $imagePath) {
            Image::create([
                'object_id' => $objectId,
                'object_type' => $objectType,
                'image' => trim($imagePath),
            ],);
        }
        return true;
    }

    private function saveTextKeys($textKeys,  $objectId, $objectType)
    {
        foreach ($textKeys as $key => $value) {
            if (!is_null($value) && $value !== '' && $value !== 0 && $value !== '0') {
                VehicleTextKey::create([
                    'name' => $key,
                    'value' => $value,
                    'object_id' => $objectId,
                    'object_type' => $objectType,
                ]);
            }
        }
    }

    private function saveImageKeys($imageKeys, $objectId, $objectType)
    {
        foreach ($imageKeys as $key => $data) {
            if (!is_null($data['data']) && $data['data'] !== '' && $data['data'] !== 0 && $data['data'] !== '0') {
                VehicleKeys::create([
                    'name' => trim($data['data'] .' '. $data['name']),
                    'icon' => $data['icon'],
                    'object_id' => $objectId,
                    'object_type' => $objectType,

                ]);
            }
        }
    }


    function createTourismProduct(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'logged_in_user_id' => ['required', 'int'],
                'title' => ['required', 'string'],
                'overview' => ['required', 'string'],
                'price' => ['required'],
                'available_time' => ['required'],
                'status' => ['required', 'string'],
                'send_discount_notification' => ['required', 'bool'],
                'images' => ['required', 'string']
            ],
        );


        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $reqData = $request->except(['logged_in_user_id', 'send_discount_notification', 'images']);
        $reqData = array_merge($reqData, ['ratings_value' => 5]);
        $data  = Tourism::create($reqData);
        $images = $this->saveImageList($request->images,  $data->id, 'tour');

        if ($data &&  $images) {
            return response()->json([
                "error" => false,
                'msg' => new TourismResource($data->refresh()),
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "Error occurred while creating product.",
            ]);;
        }
    }

    function createSecurityProduct(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'logged_in_user_id' => ['required', 'int'],
                'title' => ['required', 'string'],
                'status' => ['required', 'string'],
                'image' => ['required', 'string'],
                'available_to' => ['required', 'string'],
                'html_description' => ['required', 'string'],
            ],
        );


        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $reqData = $request->except(['logged_in_user_id']);
        $data  = Security::create($reqData);
        $images = $this->saveImageList($request->images,  $data->id, 'tour');

        if ($data &&  $images) {
            return response()->json([
                "error" => false,
                'msg' => new SecurityResource($data->refresh()),
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "Error occurred while creating product.",
            ]);;
        }
    }

    function createSaleVehicleProduct(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'logged_in_user_id' => ['required', 'int'],
                'name' => ['required', 'string'],
                'vehicle_make_id' => ['required', 'int'],
                'price' => ['required'],
                'status' => ['required', 'string'],
                'model' => ['required', 'string'],
                'color' => ['required', 'string'],
                'send_discount_notification' => ['required', 'bool'],
                'images' => ['required', 'string'],
                'image_Keys' => ['required', 'array'],
                'text_keys' => ['required', 'array'],
            ],
        );


        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }


        $data  = VehicleSale::create([
            'name' => $request->name,
            'vehicle_make_id' => $request->vehicle_make_id,
            'model' => $request->model,
            'color' => $request->color,
            'price' => $request->price,
            'discount' => $request->discount,
            'quantity' => $request->quantity,
            'status' => $request->status,
        ]);
        $images = $this->saveImageList($request->images,  $data->id, 'sale_vehicle');
        $textKeys = $this->saveTextKeys($request->text_keys,  $data->id, 'sale_vehicle');
        $imageKeys = $this->saveImageKeys($request->image_Keys,  $data->id, 'sale_vehicle');

        if ($data &&  $images) {
            return response()->json([
                "error" => false,
                'msg' => new VehicleSaleResource($data->refresh()),
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "Error occurred while creating product.",
            ]);;
        }
    }


    function createSaleAccommodationProduct(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'logged_in_user_id' => ['required', 'int'],
                'name' => ['required', 'string'],
                'price' => ['required'],
                'status' => ['required', 'string'],
                'region' => ['required', 'string'],
                'city' => ['required', 'string'],
                'send_discount_notification' => ['required', 'bool'],
                'images' => ['required', 'string'],
                'image_Keys' => ['required', 'array'],
                'text_keys' => ['required', 'array'],
            ],
        );


        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }


        $data  = AccommodationSale::create([
            'name' => $request->name,
            'region' => $request->region,
            'city' => $request->city,
            'price' => $request->price,
            'discount' => $request->discount,
            'status' => $request->status,
        ]);
        $images = $this->saveImageList($request->images,  $data->id, 'sale_accomm');
        $textKeys = $this->saveTextKeys($request->text_keys,  $data->id, 'sale_accomm');
        $imageKeys = $this->saveImageKeys($request->image_Keys,  $data->id, 'sale_accomm');

        if ($data &&  $images) {
            return response()->json([
                "error" => false,
                'msg' => new AccommodationSaleResource($data->refresh()),
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "Error occurred while creating product.",
            ]);;
        }
    }


    function createRentVehicleProduct(Request $request)  {
        $validator = Validator::make(
            $request->all(),
            [
                'logged_in_user_id' => ['required', 'int'],
                'type' => ['required', 'string', 'max:3'],
                'name' => ['required', 'string'],
                'vehicle_make_id' => ['required', 'int'],
                'price' => ['required'],
                'driver_fee' => ['required'],
                'status' => ['required', 'string'],
                'color' => ['required', 'string'],
                'send_discount_notification' => ['required', 'bool'],
                'images' => ['required', 'string'],
                'image_Keys' => ['required', 'array'],
            ],
        );


        if ($validator->fails()) {
            return response()->json([
                "error" => true,
                'msg' => $validator->errors()->first(),
            ]);
        }

        $data  = VehicleRent::create([
            'name' => $request->name,
            'vehicle_make_id' => $request->vehicle_make_id,
            'model' => $request->model,
            'color' => $request->color,
            'price' => $request->price,
            'discount' => $request->discount,
            'quantity' => $request->quantity,
            'driver_fee' => $request->driver_fee,
            'location' => $request->location,
            'free_cancellation_after' => $request->free_cancellation_after,
            'type' => $request->type,
            'status' => $request->status,

        ]);
        $images = $this->saveImageList($request->images,  $data->id, 'vehicle_rent');
        $imageKeys = $this->saveImageKeys($request->image_Keys,  $data->id, 'vehicle_rent');

        if ($data &&  $images) {
            return response()->json([
                "error" => false,
                'msg' => new VehicleRentResource($data->refresh()),
            ]);
        } else {
            return response()->json([
                "error" => true,
                'msg' => "Error occurred while creating product.",
            ]);;
        }

        return $request;
    }
}
