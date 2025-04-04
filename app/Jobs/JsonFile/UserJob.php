<?php

namespace App\Jobs\JsonFile;

use App\Data\Profile;
use App\Helper\Tools;
use App\Models\Center;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Hash;

class UserJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private Center $center)
    {
        $this->onConnection('database');
        $this->onQueue('user');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        for ($i = 1; $i <= $this->center->office; $i++) {
            $number_format = 'B'.sprintf('%02d', $i);
            $accronym = Tools::makeAcronym($this->center->label).''.$i;
            $office = str_replace("(","",$accronym);
            $userName = $this->center->code . '_' . $office . "_" . $number_format;
            $password = $userName . "@2025";

            $newUser = new User();
            $newUser->profile = Profile::REPRESENTANT;
            $newUser->office = $userName;
            $newUser->name = $userName;
            $newUser->firstName  = $password;
            $newUser->username  = $userName;
            $newUser->password  = Hash::make($password);
            $newUser->is_locked = 0;
            $newUser->center_id  = $this->center->id;
            $newUser->save();
        }
    }
}
