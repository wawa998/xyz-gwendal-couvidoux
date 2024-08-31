<?php

namespace App\Jobs;

use App\Models\Track;
use App\Players\Player;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchTrackInfo implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Track $track
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(Player $player): void
    {
        $details = $player->trackDetails($this->track->url);

        $this->track->player = $details->player_id;
        $this->track->player_track_id = $details->track_id;
        $this->track->player_thumbnail_url = $details->thumbnail_url;

        $this->track->save();
    }
}
