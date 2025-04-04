<?php

namespace App\Console\Commands;

use App\Jobs\JsonFile\CantonJob;
use App\Models\Department;
use App\Models\Provincy;
use Illuminate\Console\Command;
use Illuminate\Support\LazyCollection;

class StartApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('---- Start');

        /*$this->info('---- Cleanning database');
        Artisan::call('migrate:fresh');
        $this->info('---- Finish cleanning database');
        */


       /* $this->info('---- Creating worker');
        Artisan::call('queue:work database --queue=canton');
        Artisan::call('queue:work database --queue=center');
        Artisan::call('queue:work database --queue=user');
        $this->info('---- Finish creating worker');
        */

        $json = file_get_contents(public_path('Docs/') . 'centre_json_format.json');
        $provinces = new LazyCollection(json_decode($json, true));

        $this->info('---- Launch Jobs');
    
        foreach($provinces as $province) {
            $type = $province['type'];
            $provinceLabel = $province['province'];
            
            $newProvince = new Provincy();
            $newProvince->label = $provinceLabel;
            $newProvince->status = $type;
            $newProvince->save();
    
            $communes = new LazyCollection($province['communes']);
            foreach($communes as $commune) {
                $communeLabel = $commune['commune'];
                
                $newCommune = new Department();
                $newCommune->label = $communeLabel;
                $newCommune->provincy_id  = $newProvince->id;
                $newCommune->save();
                
                $cantons = new LazyCollection($commune['cantons']);
                dispatch(new CantonJob($newCommune->id, $cantons));
            }

        }
        
        $this->info('---- Finish launcing Jobs');

        $this->info('The command was successful! ----');
        return self::SUCCESS;
    }
}
