<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $message;
    public $subject;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $message, $subject)
    {
        $this->user = $user;
        $this->message = $message;
        $this->subject = $subject;
    }

    public function build()
    {
        $message = $this->message;
        return $this->from("dpapa9726@gmail.com", "Khaoussou")
            ->subject($this->subject)
            ->view('emails.student-email')
            ->with([
                "content" => $message
            ]);
    }
}
