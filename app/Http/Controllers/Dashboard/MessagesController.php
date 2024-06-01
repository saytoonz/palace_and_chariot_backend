<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardChatResource;
use App\Models\ChatList;
use App\Models\ChatMessage;
use App\Traits\ApiResponseTrait;
use Exception;

class MessagesController extends Controller

{

use ApiResponseTrait;

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


    // public function sendMessage(Request $request)
    // {
    //     $validator = Validator::make(
    //         $request->all(),
    //         [
    //             'from' => 'required',
    //             'message' => 'required',
    //             'object_id' => 'required',
    //             'object_type' => 'required',
    //         ]
    //     );

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'error' => true,
    //             'msg' => 'Missing params',
    //         ]);
    //     }

    //     try {

    //         $chat =  ChatMessage::create($request->all());
    //         if ($chat) {
    //             ChatList::updateOrCreate(
    //                 [
    //                     'to' => request('object_id'),
    //                     'from' =>  request('from'),
    //                     'object_type' => request('object_type'),
    //                 ],
    //                 [
    //                     'to' =>  request('object_id'),
    //                     'owner' => request('from'),
    //                     'from' => request('from'),
    //                     'message' => request('message'),
    //                     'object_id' => request('object_id'),
    //                     'object_type' => request('object_type'),

    //                 ],
    //             );

    //             $chatListItem =
    //              ChatList::updateOrCreate(
    //                 [
    //                     'to' =>  request('from'),
    //                     'from' => request('object_id'),
    //                     'object_type' => request('object_type'),
    //                 ],
    //                 [
    //                     'owner' => request('object_id'),
    //                     'to' =>  request('from'),
    //                     'from' => request('object_id'),
    //                     'message' => request('message'),
    //                     'object_id' => request('object_id'),
    //                     'object_type' => request('object_type'),

    //                 ],
    //             );
    //             $chatListItem->unread = $chatListItem->unread + 1;
    //             $chatListItem->save();

    //             // $notifs = Notification::where("uid", request('to'))->get()->first();
    //             // if ($notifs && $notifs->push_messages == 1) {
    //             //     (new PushNotificationController)->SendPush(request('to'), "chat");
    //             // }
    //         }
    //         return response()->json([
    //             'error' => false,
    //             'msg' => 'success',
    //             'data' => new ChatResource($chat->refresh()),
    //         ]);
    //     } catch (Exception $e) {
    //         return $e;
    //         return response()->json([
    //             'error' => true,
    //             'msg' => 'An error occurred...',
    //         ]);
    //     }
    // }


    public function getChats($appUserId, $objectId, $objectType, $quantity)
    {
        $response = array("error" => FALSE);
        try {
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
            $response["error"] = true;
            $response["message"] = "Server error!";
            return $response;
        }
    }

    public function getNewChats($appUserId, $objectId, $objectType,  $lastId)
    {
        $response = array("error" => FALSE);
        try {
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
