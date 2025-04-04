<?php

namespace App\Console\Commands;

use App\Jobs\ExportUsersJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ManageUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:manage-users';

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
        $this->info('---- Create candidates');
        Artisan::call('db:seed --class=CandidateSeeder');
        $this->info('---- Finish creating candidates');

        $this->info('---- Create Specific users');
        Artisan::call('db:seed --class=UserSeeder');
        $this->info('---- Finish creating specific users');

        dispatch(new ExportUsersJob());
    }
}
