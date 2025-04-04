<?php

namespace App\Jobs\JsonFile;

use App\Models\Locality;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\LazyCollection;

class CantonJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $commune_id, private LazyCollection $cantons)
    {
        $this->onConnection('database');
        $this->onQueue('canton');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->cantons as $canton) {

            $cantonLabel = $canton['canton'];
            
            $newLocality = new Locality();
            $newLocality->label = $cantonLabel;
            $newLocality->department_id  = $this->commune_id;
            $newLocality->save();

            $centers = new LazyCollection($canton['centres']);
            dispatch(new CenterJob($newLocality->id, $centers));

        }
    }
}
