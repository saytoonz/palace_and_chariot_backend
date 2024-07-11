<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Resources\DashboardChatResource;
use App\Models\ActivityLog;
use App\Models\AppUser;
use App\Models\ChatList;
use App\Models\ChatMessage;
use App\Models\DashboardUser;
use App\Traits\ApiResponseTrait;
use App\Traits\CountryTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessagesController extends Controller

{

use ApiResponseTrait;
use CountryTrait;

    public function getChatList()
    {
        $response = array("error" => FALSE);
        try {
            $list = ChatList::orderBy('updated_at', 'DESC')->paginate();

            $response["message"] = "success";
            $response["data"] =  DashboardChatResource::collection($list);
            $response["error"] = false;
            $response['paginate'] = $list == NULL ? NULL : [
                'previous_page_url' => $list->appends(request()->input())->previousPageUrl(),
                'next_page_url' => $list->appends(request()->input())->nextPageUrl(),
                'number_per_page' => $list->perPage(),
                'total_items' => $list->total(),
            ];

        } catch (\Throwable $th) {
            // throw $th;
            $response["error"] = true;
            $response["message"] = "Server error!";
        }
        return $response;
    }


    public function sendMessage(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'to' =>  ['required',  'int'],
                'admin' =>  ['required',  'int'],
                'message' =>  ['required',  'String'],
                'object_id' =>  ['required',  'int'],
                'object_type' =>  ['required',  'String'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'msg' => 'Missing params',
            ]);
        }

        try {

            $chat =  ChatMessage::create($request->all());
            if ($chat) {
                $chatListItem =

                ChatList::updateOrCreate(
                    [
                        'owner' => request('to'),
                        'object_type' => request('object_type'),
                        'object_id' => request('object_id'),
                    ],
                    [
                        'owner' => request('to'),
                        'from' => request('to'),
                        'admin' => request('from'),
                        'message' => request('message'),
                        'object_id' => request('object_id'),
                        'object_type' => request('object_type'),

                    ],
                );



                $chatListItem->unread = $chatListItem->unread + 1;
                $chatListItem->save();

                /// Create activity log
                $appUser = AppUser::find($request->to);
                $dashUser = DashboardUser::find($request->admin);
                ActivityLog::create([
                    'dashboard_user_id' => $request->admin,
                    'app_user_id' => $request->to,
                    'activity' => 'Responded to a message from ['.$appUser->first_name.' '.$appUser->last_name.']',
                    'country' => $dashUser->country,
                    'device' => $request->header('User-Agent'),
                ]);




                // $notifs = Notification::where("uid", request('to'))->get()->first();
                // if ($notifs && $notifs->push_messages == 1) {
                //     (new PushNotificationController)->SendPush(request('to'), "chat");
                // }
            }
            return response()->json([
                'error' => false,
                'msg' => 'success',
                'data' => new ChatResource($chat->refresh()),
            ]);
        } catch (Exception $e) {
            return $e;
            return response()->json([
                'error' => true,
                'msg' => 'An error occurred...',
            ]);
        }
    }


    public function getChats($appUserId, $objectId, $objectType, $quantity)
    {
        $response = array("error" => FALSE);
        try {
            //Update Chat messages set unread to false
            //That is what dashboard uses to check
            // unread messages
                ChatMessage::where('from', $appUserId)
                ->where('object_id', $objectId)
                ->where('object_type', $objectType)
                ->update(['unread' => false]) ;


            $chats = ChatMessage::where(function ($query) use ($appUserId) {
                $query->where('from', $appUserId);
                $query->orwhere('to', $appUserId);
            })
                ->where('object_id', $objectId)
                ->where('object_type', $objectType)
                ->orderBy('id', 'DESC')
                ->paginate($quantity);

            return $this->ApiResponse(true, 'chats', null, $chats, true);
        } catch (\Throwable $th) {
            // throw $th;
            $response["error"] = true;
            $response["message"] = "Server error!";
            return $response;
        }
    }

    public function getNewChats($appUserId, $objectId, $objectType,  $lastId)
    {
        $response = array("error" => FALSE);
        try {
            //Update Chat messages set unread to false
            //That is what dashboard uses to check
            // unread messages
            ChatMessage::where('from', $appUserId)
            ->where('object_id', $objectId)
            ->where('object_type', $objectType)
            ->update(['unread' => false]) ;


            $chats = ChatMessage::where('id', '>', $lastId)->where(function ($query) use ($appUserId) {
                $query->where('from', $appUserId);
                $query->orwhere('to', $appUserId);
            })
                ->where('object_id', $objectId)
                ->where('object_type', $objectType)
                ->orderBy('id', 'DESC')
                ->paginate(100);


            return $this->ApiResponse(true, 'chats', null, $chats, true);
        } catch (\Throwable $th) {
            // throw $th;
            $response["error"] = true;
            $response["message"] = "Server error!";
            return $response;
        }
    }

}
