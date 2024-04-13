<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVoteResults extends Mailable
{
    use Queueable, SerializesModels;

    public array $results;

    public function __construct($results)
    {
        $this->results = $results;
    }

    public function build()
    {
        return $this->subject('Poll Results')
            ->view('emails.results')
            ->with('results', $this->results);
    }
}
