<?php

namespace App\Http\Controllers;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with('user')->latest()->limit(50)->get();
        return view('chat', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required',
        ]);

        Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);
        return back();
    }
}
