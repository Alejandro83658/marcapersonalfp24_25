<?php

namespace Database\Seeders;

use App\Models\ParticipanteProyecto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantesProyectosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('participantes_proyectos')->truncate();

        $combinaciones = [];

        ParticipanteProyecto::factory(10)->make()->each(function ($participante) use (&$combinaciones) {
            $key = "{$participante->user_id}-{$participante->proyecto_id}";


            while (isset($combinaciones[$key])) {
                $participante->user_id = rand(1, 10);
                $participante->proyecto_id = rand(1, 10);
                $key = "{$participante->user_id}-{$participante->proyecto_id}";
            }

            $combinaciones[$key] = true;
            $participante->save();
        });
    }
}
