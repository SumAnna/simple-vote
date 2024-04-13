<?php

namespace App\Jobs;

use App\Models\Vote;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreVoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    protected $userId, $optionId, $ipAddress;

    public function __construct($userId, $optionId, $ipAddress)
    {
        $this->userId = $userId;
        $this->optionId = $optionId;
        $this->ipAddress = $ipAddress;
    }

    public function handle()
    {
        $vote = new Vote();
        $vote->user_id = $this->userId;
        $vote->option_id = $this->optionId;
        $vote->ip_address = $this->ipAddress;

        try {
            $location = geoip()->getLocation($this->ipAddress);
            $vote->location = $location->city . ', ' . $location->country;
        } catch (Exception $e) {
            $vote->location = 'London, UK';
        }

        $vote->save();
    }
}

