<?php

namespace Database\Seeders;

use App\Models\Ciclo;
use App\Models\User;
use App\Models\UsersCiclos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersCiclosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    UsersCiclos::truncate();
    $users = User::all();
    $ciclos = Ciclo::all();
    foreach ($users as $user) {
        $numCiclos = rand(0, 2);
        $ciclosUser = [];
        for ($i = 0; $i < $numCiclos; $i++) {
            $ciclo = $ciclos->random();
            if (!in_array($ciclo->id, $ciclosUser)) {
                $user->ciclos()->attach($ciclo->id);
                $ciclosUser[] = $ciclo->id;
            }
        }
    }
}

}
