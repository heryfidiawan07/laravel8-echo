<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PrivateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $from;
    public $message;

    /**
    * The name of the queue connection to use when broadcasting the event.
    *
    * @var string
    */
    public $connection = 'redis';

    /**
    * The name of the queue on which to place the broadcasting job.
    *
    * @var string
    */
    public $queue = 'default';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $from, $message)
    {
        // dd($user);
        $this->user = $user;
        $this->from = $from;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user-'.$this->user->id);
    }

    /*
     * The Event's broadcast name.
     * 
     * @return string
     */
    public function broadcastAs()
    {
        return 'PrivateMessage';
    }
    
    /*
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'from' => $this->from,
            'message' => $this->message
        ];
    }
}
