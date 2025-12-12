<?php

namespace App\Jobs;

use App\Events\MessageReceived;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $messageId;

    public function __construct(int $messageId)
    {
        $this->messageId = $messageId;
    }

    public function handle(): void
    {
        $msg = Message::find($this->messageId);
        if (! $msg) {
            return;
        }

        // Basic processing: sanitize and add metadata
        $cleanBody = strip_tags($msg->body);
        $msg->body = $cleanBody;

        $msg->metadata = [
            'length' => mb_strlen($cleanBody),
            'processed_by' => 'ProcessMessageJob',
        ];

        $msg->processed_at = now();
        $msg->save();

        // Broadcast processed message to all clients
        broadcast(new MessageReceived($msg))->toOthers();
    }
}
