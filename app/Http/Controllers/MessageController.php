<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessMessage;
use App\Models\Message;
use Illuminate\Http\Request;


class MessageController extends Controller
{
    public function __construct()
    {
        // ensure only authenticated users can post
        $this->middleware('auth');
    }

    /**
     * Store message and dispatch job for processing & broadcast.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        // Always use authenticated user as sender
        $message = Message::create([
            'sender_id' => $request->user()->id,
            'body' => $data['message'],
        ]);

        // Dispatch queued job for processing and broadcasting
        ProcessMessage::dispatch($message->id);

        return response()->json(['id' => $message->id], 201);
    }
}
