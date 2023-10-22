<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $emailProf;
    public $prof;
    public $message;
    public $subject;
    public function __construct($user, $emailProf, $prof, $message, $subject)
    {
        $this->user = $user;
        $this->emailProf = $emailProf;
        $this->prof = $prof;
        $this->message = $message;
        $this->subject = $subject;
    }

    public function build()
    {
        $message = $this->message;
        $prof = $this->prof;
        $email = $this->emailProf;

        return $this->from($email, $prof->name)
            ->subject($this->subject)
            ->view("emails.prof-email")
            ->with([
                "content" => $message
            ]);
    }
}
