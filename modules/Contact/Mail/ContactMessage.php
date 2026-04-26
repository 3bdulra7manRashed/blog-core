<?php

namespace Modules\Contact\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use Modules\Contact\Models\ContactMessage as ContactMessageModel;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The contact form message model.
     */
    public ContactMessageModel $message;

    /**
     * The dynamic theme color for the email.
     */
    public string $themeColor;

    /**
     * Create a new message instance.
     */
    public function __construct(ContactMessageModel $message)
    {
        $this->message = $message;

        $activeTheme = active_theme();
        
        $defaultColors = [
            'bayan' => '#0F766E',
            'gpma' => '#1F3A6E',
            'classic' => '#c37c54',
        ];
        
        $this->themeColor = setting('primary_color', $defaultColors[$activeTheme] ?? '#0F766E');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [$this->message->email],
            subject: 'رسالة جديدة من نموذج التواصل - ' . $this->message->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'senderName' => $this->message->name,
                'senderEmail' => $this->message->email,
                'senderPhone' => $this->message->phone,
                'senderMessage' => $this->message->message,
                'themeColor' => $this->themeColor,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
