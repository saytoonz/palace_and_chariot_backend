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
use App\Models\Security;
use App\Models\Tourism;
use App\Models\VehicleRent;
use App\Models\VehicleSale;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function getProducts(Request $request)  {
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
      ->merge($accomodation)
      ;

  return $all->sortBy('created_at')->values();
    }
}
