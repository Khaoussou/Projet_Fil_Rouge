<?php

namespace App\Console\Commands;

use App\Models\Absence;
use App\Models\PlanificationSessionCour;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class AbsenceCommande extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:absence';

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
        $dateNow = Carbon::now();
        $dateFormat = $dateNow->format('Y-m-d');
        $allSessions = PlanificationSessionCour::where('date', $dateFormat)->get();
        $tab = [];
        foreach ($allSessions as $session) {
            $currentDate = Carbon::parse($session->date . 'T' . $session->heure_debut);
            if ($currentDate->eq($dateNow)) {
                $tab[] = [
                    'inscription_id' => 26,
                    'planification_session_cour_id' => $session->id,
                ];
            }
        }
        Absence::insert($tab);
    }
}
