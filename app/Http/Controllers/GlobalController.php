<?php

namespace App\Http\Controllers;

use App\Chat;
use App\ChatMessage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GlobalController extends Controller
{
    public function __construct()
    {
//        dd(User::all());
//        dd(Auth::id());
    }

    public function UserChat($user_id = null)
    {
        $friends = User::where('id', '!=', Auth::user()->id)->get();
        if (is_null($user_id)) {
            return View::share('friends', $friends);
        } else {
            $friend = User::find($user_id);

            $chat = Chat::where('sender', Auth::id())
                ->where('receiver', $friend->id)
                ->first();
            if (is_null($chat)) {
                $chat = Chat::where('receiver', Auth::id())
                    ->where('sender', $friend->id)
                    ->first();
                if (is_null($chat)) {
                    $chat = new Chat();
                    $chat->sender = Auth::id();
                    $chat->receiver = $friend->id;
                    $chat->save();

                    $messages = ChatMessage::where('chat_id', $chat)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $arr = [
                        'friends' => $friends,
                        'friend' => $friend,
                        'chat' => $chat,
                        'messages' => $messages,
                    ];
                    return View::share($arr);
                } else {
                    $messages = ChatMessage::where('chat_id', $chat->id)
                        ->orderBy('created_at', 'asc')
                        ->get();
                    $arr = [
                        'friends' => $friends,
                        'friend' => $friend,
                        'chat' => $chat,
                        'messages' => $messages,
                    ];
                    return View::share($arr);
                }
            } else {
                $messages = ChatMessage::where('chat_id', $chat->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
                $arr = [
                    'friends' => $friends,
                    'friend' => $friend,
                    'chat' => $chat,
                    'messages' => $messages,
                ];
                return View::share($arr);
            }
        }
    }
}
