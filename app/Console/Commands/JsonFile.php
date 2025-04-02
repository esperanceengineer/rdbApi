<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Center;
use App\Models\Department;
use App\Models\Locality;
use App\Models\Provincy;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Data\Profile;
use App\Helper\Tools;

class JsonFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:json-file';

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
        $this->info('---- Running');

        // Read the contents of the JSON file
        $json = file_get_contents(public_path('Docs/') . 'centre_json_format.json');

        // Decode JSON into an associative array
        $provinces = json_decode($json, true);

        $progress = $this->output->createProgressBar(count($provinces));
        $progress->start();

        foreach ($provinces as $data) {
            $type = $data['type'];
            $province = $data['province'];

            $newProvince = new Provincy();
            $newProvince->label = $province;
            $newProvince->status = $type;
            $newProvince->save();


            $communes = $data['communes'];

            foreach ($communes as $commune) {
                $communeLabel = $commune['commune'];
                $cantons = $commune['cantons'];

                $newCommune = new Department();
                $newCommune->label = $communeLabel;
                $newCommune->provincy_id  = $newProvince->id;
                $newCommune->save();

                foreach ($cantons as $canton) {
                    $cantonLabel = $canton['canton'];
                    $newCentres = $canton['centres'];

                    $newLocality = new Locality();
                    $newLocality->label = $cantonLabel;
                    $newLocality->department_id  = $newCommune->id;
                    $newLocality->save();


                    foreach ($newCentres as $newCentre) {
                        $centerLabel = $newCentre['centre'];
                        $code = $newCentre['code'];
                        $nbrbureaux = intval($newCentre['nbrbureaux']);

                        $newCentre = new Center();
                        $newCentre->label = $centerLabel;
                        $newCentre->code = $code;
                        $newCentre->office = $nbrbureaux;
                        $newCentre->locality_id  = $newLocality->id;
                        $newCentre->save();

                        for ($i = 1; $i <= $nbrbureaux; $i++) {
                            $firstString  = substr($newProvince->label, 0, 3);
                            $number_format = 'B'.sprintf('%02d', $i);
                            $accronym = Tools::makeAcronym($centerLabel).''.$i;
                            $office = str_replace("(","",$accronym);
                            $userName = $firstString . '_' . $office . "_" . $number_format;
                            $password = $userName . "@2025";

                            $newUser = new User();
                            $newUser->profile = Profile::REPRESENTANT;
                            $newUser->office = $userName;
                            $newUser->name = $userName;
                            $newUser->username  = $userName;
                            $newUser->password  = Hash::make($password);
                            $newUser->is_locked = 0;
                            $newUser->center_id  = $newCentre->id;
                            $newUser->save();
                        }
                    }
                }
            }

            $progress->advance();
            $this->newLine();
        }

        $progress->finish();
        $this->info('---- Json File Saved! ----');
        $this->info('The command was successful! ----');

        return self::SUCCESS;
    }
}
