<?php

namespace App\Listeners;

use App\Models\SnsNotification;
use Illuminate\Support\Carbon;

class HandleSnsNotification
{
    /**
     * Handle the event.
     *
     * @param  \Rennokki\LaravelSnsEvents\Events\SnsNotification  $event
     * @return void
     */
    public function handle($event)
    {
        $message = $event->payload['message'];

        $notification = new SnsNotification();
        $notification->message_id = $message['MessageId'];
        $notification->message = $message['Message'];
        $notification->topic_arn = $message['TopicArn'];
        $notification->message_timestamp = Carbon::parse($message['Timestamp']);
        $notification->raw = json_encode($message);
        $notification->save();
    }
}
