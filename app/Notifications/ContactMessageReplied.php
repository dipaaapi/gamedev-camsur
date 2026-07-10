<?php

namespace App\Notifications;

use App\Models\ContactMessage;
use App\Models\ContactMessageReply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ContactMessageReplied extends Notification
{
    use Queueable;

    protected $contactMessage;
    protected $reply;

    /**
     * Create a new notification instance.
     */
    public function __construct(ContactMessage $contactMessage, ContactMessageReply $reply)
    {
        $this->contactMessage = $contactMessage;
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = [];
        $mode = $this->reply->reply_mode ?? 'both';

        if (($mode === 'email' || $mode === 'both') && $this->contactMessage->email) {
            $channels[] = 'mail';
        }
        
        // Log/send SMS if mobile number is present and mode matches
        if (($mode === 'sms' || $mode === 'both') && $this->contactMessage->mobile_number) {
            $this->sendSms();
        }

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reply to your inquiry: ' . strtok($this->contactMessage->purpose, ':'))
            ->greeting('Hello ' . $this->contactMessage->name . ',')
            ->line('We have received a reply to your inquiry regarding "' . $this->contactMessage->purpose . '".')
            ->line('Reply message:')
            ->line('"' . $this->reply->message . '"')
            ->line('Thank you for contacting us!');
    }

    /**
     * Simulate sending an SMS message.
     */
    protected function sendSms(): void
    {
        Log::info("SMS notification sent to {$this->contactMessage->mobile_number}: " .
            "Hello {$this->contactMessage->name}, we replied to your inquiry: \"{$this->reply->message}\"");
    }
}
