<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExportUsersJob implements ShouldQueue
{
    use Queueable;

        /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onConnection('database');
        $this->onQueue('user');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $filePath = public_path('Docs/utilisateurs.csv');

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    
        $handle = fopen($filePath, 'w');
        fputcsv($handle, [
            'COMMUNE',
            'CANTON',
            'CENTRE',
            'BUREAU',
            'IDENTIFIANT',
            'MOT DE PASSE'
        ]);
    
        User::with('center.locality.department')->lazyById(100, 'id')
            ->each(function ($user) use ($handle) {
                $commune = is_null($user->center_id) ? "Aucune commune" : $user->center->locality->department->label;
                $canton = is_null($user->center_id) ? "Aucun canton" : $user->center->locality->label;
                $centre = is_null($user->center_id) ? "Aucun centre" : $user->center->label;
                fputcsv($handle, [
                    $commune,
                    $canton,
                    $centre,
                    $user->office,
                    $user->username,
                    $user->firstname
                ]);
            });
    
        fclose($handle);
    }
}
