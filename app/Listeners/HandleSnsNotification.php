<?php

namespace App\Listeners;

use App\Actions\StoreMail;
use App\Models\SnsNotification;
use Illuminate\Support\Carbon;

class HandleSnsNotification
{
    /**
     * @var \App\Actions\StoreMail
     */
    public $action;

    public function __construct(StoreMail $action)
    {
        $this->action = $action;
    }

    /**
     * Check if the given message is a SES notification message
     *
     * @param mixed $message
     * @return bool
     */
    private function isSESMessage($message)
    {
        return is_array($message) && isset($message['mail']);
    }

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

        $message = json_decode($message['Message'], true);
        if ($this->isSESMessage($message)) {
            $this->action->execute($message);
        }
    }
}
