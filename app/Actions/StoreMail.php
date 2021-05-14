<?php

namespace App\Actions;

use App\Models\Mail;
use App\Models\MailRecipient;
use App\Models\Recipient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class StoreMail
{
    const TAG_HEADER_NAME = 'X-SES-MESSAGE-TAGS';

    /**
     * Get resolved recipients from the given message.
     *
     * @param array $message
     * @return string[]
     */
    private function getRecipients($message)
    {
        if ($message['notificationType'] === 'Delivery') {
            return $message['delivery']['recipients'];
        }

        return array_column($message['bounce']['bouncedRecipients'], 'emailAddress');
    }

    private function getTagsFromHeader($message)
    {
        $tags = [];
        foreach ($message['mail']['headers'] as $header) {
            if ($header['name'] === self::TAG_HEADER_NAME) {
                $exploded = explode(',', $header['value']);

                foreach ($exploded as $value) {
                    $tag = explode('=', $value);

                    if (count($tag) === 2) {
                        $tags[$tag[0]] = $tag[1];
                    }
                }
            }
        }

        return $tags;
    }

    /**
     * Store mail from the given notification message.
     *
     * @param  array  $message
     * @return \App\Models\Mail
     */
    public function execute($message)
    {
        $messageId = $message['mail']['messageId'];
        $source = $message['mail']['source'];
        $sentAt = $message['mail']['commonHeaders']['date'];
        $subject = mb_decode_mimeheader($message['mail']['commonHeaders']['subject']);

        $mail = Mail::firstOrCreate(
            [
                'message_id' => $messageId,
            ],
            [
                'subject' => $subject,
                'source_email' => $source,
                'sent_at' => Carbon::parse($sentAt)->utc(),
            ]
        );

        $tags = $this->getTagsFromHeader($message);
        foreach ($tags as $type => $tag) {
            $mail->attachTag($tag, $type);
        }

        $allRecipients = $message['mail']['destination'];
        $recipients = $this->getRecipients($message);

        $status = Str::lower($message['notificationType']);
        $resolvedAt = $message[$status]['timestamp'];

        foreach ($allRecipients as $recipient) {
            $storedRecipient = Recipient::firstOrCreate(['email' => $recipient]);

            $isResolved = in_array($storedRecipient->email, $recipients);
            $mailRecipient = MailRecipient::firstOrNew(
                [
                    'recipient_id' => $storedRecipient->id,
                    'mail_id' => $mail->id,
                ],
                [
                    'email' => $storedRecipient->email,
                ]
            );

            if ($isResolved && $mailRecipient->is_not_resolved) {
                $mailRecipient->status = $status;
                $mailRecipient->resolved_at = $resolvedAt;

                $storedRecipient->unknown_count -= $mail->wasRecentlyCreated ? 0 : 1;
                if ($status === 'delivery') {
                    $storedRecipient->delivered_count += 1;
                } else {
                    $storedRecipient->bounced_count += 1;
                }
            }

            if ($mail->wasRecentlyCreated) {
                $storedRecipient->total_count += 1;
                $storedRecipient->unknown_count += $isResolved ? 0 : 1;
            }

            $storedRecipient->save();
            $mailRecipient->save();
        }

        Cache::forget('dashboard-data');
    }
}
