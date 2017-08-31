<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        $messages = Message::all();
        return view('welcome', compact('messages'));
    }
    public function store(Request $request)
    {
        $message = new Message();
        $message->msg = $request->input('msg');
        $message->save();
        return response()->json([
            "data" => $message
        ]);
    }

    public function ajax()
    {
        if (Message::where('check', 0)->count() > 0) {
            $data = Message::where('check', 0)->first();
            $data->check = 1;
            $data->save();
            return response()->json([
                'msg' => $data->msg
            ]);
        } else return response()->json([
            'msg' => null
        ]);
    }
}
