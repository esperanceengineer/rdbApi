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
        $filePath = public_path('Docs/export.csv');

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    
        $handle = fopen($filePath, 'w');
        fputcsv($handle, [
            'Centre',
            'Bureau',
            'Identifiant',
            'Mot de passe'
        ]);
    
        User::with('center')->lazyById(100, 'id')
            ->each(function ($user) use ($handle) {
                $label = is_null($user->center_id) ? "Aucun centre" : $user->center->label;
                fputcsv($handle, [
                    $label,
                    $user->office,
                    $user->username,
                    $user->firstname
                ]);
            });
    
        fclose($handle);
    }
}
