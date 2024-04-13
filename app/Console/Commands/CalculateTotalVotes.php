<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVoteResults;

class CalculateTotalVotes extends Command
{
    protected $signature = 'app:calculate-total-votes';
    protected $description = 'Calculating poll results and sending email.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $results = Question::getVoteResults();
        $email = env('POLL_RESULTS_EMAIL');

        if (!$email) {
            $this->error('The POLL_RESULTS_EMAIL environment variable is not set.');

            return;
        }

        Mail::to($email)
            ->send(new SendVoteResults($results));
        $this->info('Votes results calculated and email sent.');
    }
}


