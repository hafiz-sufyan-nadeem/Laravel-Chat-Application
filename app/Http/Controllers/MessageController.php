<?php

namespace App\Http\Controllers;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Events\MessageSent;

class MessageController extends Controller
{
    public function index() {
        ini_set('memory_limit','512M');
        $messages = Message::with('user')->latest()->limit(50)->get();
        return view('chat', compact('messages'));
    }


    public function store(Request $request) {
        $request->validate(['message'=>'required']);

        $message = Message::create([
            'user_id'=>auth()->id(),
            'message'=>$request->message
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status'=>'Message sent']);
    }
    public function sendMessage(Request $request)
    {
        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        broadcast(new MessageSent($message))->toOthers();

        return ['status' => 'Message Sent!'];
    }
}
