<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccommodationSaleResource;
use App\Http\Resources\ApartmentRentResource;
use App\Http\Resources\EventServiceRentResource;
use App\Http\Resources\HotelRentResource;
use App\Http\Resources\SecurityResource;
use App\Http\Resources\TourismResource;
use App\Http\Resources\TravelLocationResource;
use App\Http\Resources\VehicleRentResource;
use App\Http\Resources\VehicleSaleResource;
use App\Models\AccommodationSale;
use App\Models\AccommodationSaleRequest;
use App\Models\ApartmentRent;
use App\Models\ApartmentRequest;
use App\Models\AppUser;
use App\Models\ChatMessage;
use App\Models\EventRentRequest;
use App\Models\EventServiceRent;
use App\Models\HotelRent;
use App\Models\HotelRequest;
use App\Models\Security;
use App\Models\SecurityRequest;
use App\Models\Tourism;
use App\Models\TourismRequest;
use App\Models\TravelLocations;
use App\Models\TravelRequest;
use App\Models\VehicleRent;
use App\Models\VehicleRentRequest;
use App\Models\VehicleSale;
use App\Models\VehicleSaleRequest;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class DashboardController extends Controller
{
    function getStatistics()
    {

        $totalCustomers = AppUser::count();
        $totalMessages = ChatMessage::count();

        $vRent = VehicleRentRequest::count();
        $vSale = VehicleSaleRequest::count();
        $travel = TravelRequest::count();
        $tour = TourismRequest::count();
        $security = SecurityRequest::count();
        $hotel = HotelRequest::count();
        $events = EventRentRequest::count();
        $apartment = ApartmentRequest::count();
        $accomodation = AccommodationSaleRequest::count();


        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => [
                "total_services" => 11,
                "total_requests" => $vRent + $vSale + $travel + $tour + $security + $hotel + $events + $apartment + $accomodation,
                "total_customers" => $totalCustomers,
                "total_messages" => $totalMessages,
            ],
        ]);
    }


    function getActiveRequest(){
        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $this->getAllRequest('active'),
        ]);
    }

    function getClosedRequest(){
        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $this->getAllRequest('close'),
        ]);
    }

    function getPendingRequest(){
        return response()->json([
            'error' => false,
            'msg' => "success",
            'data' => $this->getAllRequest('pending'),
        ]);
    }


    function getAllRequest($status)
    {
        $vRent = VehicleRentRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'VRR' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Rentals/Vehicle';
            $data['data'] = new VehicleRentResource(VehicleRent::find($data->vehicle_id));
            return $data;
        });
        $vSale = VehicleSaleRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'VSR' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Sales/cars';
            $data['data'] = new VehicleSaleResource(VehicleSale::find($data->vehicle_id));
            return $data;
        });
        $travel = TravelRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'TTR' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Travel&Tourism/Travel';
            // $data['data'] = new TourismResource(Tourism::find($data->vehicle_id));
            return $data;
        });
        $tour = TourismRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'TTO' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Travel&Tourism/ Tourism';
            $data['data'] = new TourismResource(Tourism::find($data->tour_site_id));
            return $data;
        });
        $security = SecurityRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'SEC' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Security';
            $data['data'] = new SecurityResource(Security::find($data->security_id));
            return $data;
        });
        $hotel = HotelRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'HOT' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Rentals/Accommodation';
            $data['data'] = new HotelRentResource(HotelRent::find($data->rent_hotel_id));
            return $data;
        });
        $events = EventRentRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'ESR' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Rentals/Event space';
            $data['data'] = new EventServiceRentResource(EventServiceRent::find($data->rent_event_id));
            return $data;
        });
        $apartment = ApartmentRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'APT' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Rentals/Accommodation';
            $data['data'] = new ApartmentRentResource(ApartmentRent::find($data->rent_apartment_id));
            return $data;
        });
        $accomodation = AccommodationSaleRequest::where('status', $status)->get()->map(function ($data) {
            $data['request_id'] = 'SACC' . ($data->id > 100 ? '00' . $data->id :  $data->id);
            $data['category'] = 'Sales/Houses';
            $data['data'] = new AccommodationSaleResource(AccommodationSale::find($data->accommodation_id));
            return $data;
        });

        $all = collect()->merge($vRent)->merge($vSale)->merge($travel)->merge($tour)
            ->merge($security)->merge($hotel)->merge($events)
            ->merge($apartment)->merge($accomodation);

        return $all->sortBy('created_at')->values();
    }
}
