<?php

namespace App\Http\Controllers;

use App\Chat;
use App\ChatMessage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function __construct()
    {

    }
    /*
     * function to create the message and return it as a json array to be handled by AJAX or API
     * here, we create a new message by requesting
     * [ the message text, the sender, the receiver, and the exact chat ]
     * and after message is saved in the database, server returns it as JSON.
     */
    public function createMessage(Request $request)
    {
        /*
         * Validate the message to prevent null message.
         */
        $this->validate($request, [
            'message_text' => 'required | min:2 | max:200',
        ]);
        /*
         * create new ChatMessage instance to create a new message and save it in chat_messages table.
         */
        $message = new ChatMessage();
        $message->sender = Auth::id();
        $message->message_text = $request->input('message_text');
        $message->chat_id = $request->input('chat_id');
        $message->save();
        /*
         * get the user avatar to be able to view it.
         */
        $message['user_profile'] = Auth::user()->getProfile();
        /*
         * after all, we return data as json array.
         */
        return response()->json(["message" => $message]);
    }

    /*
     * After the message is successfully created, we need to deliver it to the receiver.
     * this function uses AJAX to be delivered.
     * first, We need to get the exact chat we are in.
     * then we check for the user sender in chats table and here's the explanation  =>
                *  in in chats table, we have [sender and receiver columns].
                *  when chat row is null then the first to start chat will be the sender
                *  and the friend will be the receiver.
                *  So, You may be the sender or receiver.
     * after check, we know who is the receiver and now, we can easily get his avatar :).
     * then we check for the messages sent to the receiver that is unseen (un delivered) yet.
     * finally, we deliver it to the browser as json array.
     */
    public function retrieveMessage(Request $request)
    {
        /*
         * get the Chat.
         */
        $chat = Chat::find($request->chat_id);

        /*
         * check who is the receiver
         */
        if ($chat->sender == Auth::user()->id) {
            $receiver = User::find($chat->receiver);
        } elseif ($chat->receiver == Auth::user()->id) {
            $receiver = User::find($chat->sender);
        }
        /*
         * get the unseen (undelivered) messages count
         * if there is messages => get that message.
         * change its status to delivered [ status = 1 ]
         * return it as json array.
         * else if count of undelivered message == 0
         * then we return null
         */
        if (ChatMessage::where('status', 0)
                ->where('chat_id', $request->chat_id)
                ->where('sender', '!=', Auth::user()->id)
                ->count() > 0
        ) {
            /*
             * get the message
             */
            $data = ChatMessage::where('status', 0)
                ->where('chat_id', $request->chat_id)
                ->where('sender', '!=', Auth::user()->id)
                ->first();
            /*
             * if there's data, return it, else return null
             */
            if($data){
                $data->status = 1;
                $data->save();

                $data['user_profile'] = $receiver->getProfile();
            }else{
                $data = null;
            }
            /*
             * return data as json array
             */
            return response()->json(["message" => $data]);
        }
    }

    /*
     * Messenger function is the main function to get the view.
     * this function gives some views:
        *   first the View contains all friends.
        *   second if the View contains the Chat between the Auth user and a specific friend.
     * first, we get the users we chat with.
     * here, this is a sample, no friendship, so, friends are all users except the Authenticated user.
     * and of course we pass a nullable parameter to choose which friend to chat with when get the second view option.
     * if parameter equals null, then we view only the friends in [ messenger.blade.php ]
     * But if we chat with a friend, then we are now viewing [ chat.blade.php ].
     * [ chat.blade.php ] extends [ messenger.blade.php ] which contains the list of friends.
     * in chat view, we sure have [ the exact chat, the sender, the receiver, the previous messages, the form of messaging ].
     *
     */
    public function Messenger($user_id = null)
    {
        /*
         * get all friends
         */
        $friends = User::where('id', '!=', Auth::user()->id)->get();
/*
 * get the exact friend if found
 */
        $friend = User::find($user_id);
        /*
         * return the mesenger view if no friend selected
         */
        if (is_null($friend)) {
            return view('messenger', compact('friends'));
        } else {
            /*
             * if a friend is selected, then we need to set [ the chat, sender, .... ]
             */
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

                    $messages = ChatMessage::where('chat_id', $chat->id)
                        ->orderBy('created_at', 'asc')->get();
                    return view('chat',
                        compact('friends', 'friend', 'chat', 'messages'));
                } else {
                    $messages = ChatMessage::where('chat_id', $chat->id)
                        ->orderBy('created_at', 'asc')
                        ->get();
                    return view('chat',
                        compact('friends', 'friend', 'messages', 'chat'));
                }
            } else {
                $messages = ChatMessage::where('chat_id', $chat->id)
                    ->orderBy('created_at', 'asc')
                    ->get();
                return view('chat',
                    compact('friends', 'friend', 'messages', 'chat'));
            }
        }
    }

    /*
     * this function is used to update the condition of users [ Online or Offline ]
     * and of course, connected with AJAX to update the data every period of time.
     * simply, I return some html content to replace the old ones in the view with the new status of users.
     * and then data returned as json array to be appended in the view easily.
     */
    public function chat_users()
    {
        $friends = User::where('id', '!=', Auth::user()->id)->get();

        $data = '<ul class="list-unstyled">';

        foreach ($friends as $myFriend) {
            $data .= '<li>
                    <a href="' . route("chat", [$myFriend->id]) . '">
                        <div class="row">
                            <div class="col-sm-3">
                            
                                <img src="' . $myFriend->getProfile() . '"
                                    class="img-responsive chat-image">
                            </div>
                            
                        <div class="col-sm-9">
                        ' . ucfirst($myFriend->username). ' | ';
            if ($myFriend->isOnline()) {
                $data .= 'Online';
            } else {
                $data .= 'Offline 

                </div>
                
                </div>
            </a><br>
        </li>';
            }
        }
        $data .= '</ul>';

        return response()->json($data);
    }

}
