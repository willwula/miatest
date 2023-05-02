<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected User $user)
    {
//        $this->user = $user;
    }

//    public function build()
//    {
//        return $this->view('emails.register-success')
//            ->subject('Register Success')
//            ->from(config('mail.from.address'), config('mail.from.name'));
//    }
    /**
     * Get the message envelope.
     */


    public function envelope(): Envelope
    {
//        dd($this->user);
        return new Envelope(
            subject: '註冊成功通知信',
//            tags: ['shipment'],
            metadata: [
                'user_id' => $this->user->id,
            ],
        );
    }

    /**
     * Get the message content definition.
     */

    //content()可以用with將想要帶進view的內容帶過去
    public function content(): Content
    {
        return new Content(
            view: 'emails.register-success',
            with: [
                'userName' => $this->user->name,
                'userId' => $this->user->id,
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
