<?php

namespace App\Jobs\JsonFile;

use App\Models\Center;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\LazyCollection;

class CenterJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $locality_id, private LazyCollection $centers)
    {
        $this->onConnection('database');
        $this->onQueue('center');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->centers as $center) {

            $centerLabel = $center['centre'];
            $code = $center['code'];
            $nbrbureaux = intval($center['nbrbureaux']);

            $newCentre = new Center();
            $newCentre->label = $centerLabel;
            $newCentre->code = str_replace(' ', '', $code);
            $newCentre->office = $nbrbureaux;
            $newCentre->locality_id  = $this->locality_id;
            $newCentre->save();

            dispatch(new UserJob($newCentre));
        }

    }
}
