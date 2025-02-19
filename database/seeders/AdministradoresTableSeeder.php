<?php

namespace Database\Seeders;

use App\Models\Administrador;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministradoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Administrador::truncate();

        $usuarios = User::all();

        $cantidadAdmins = rand(1, 3);

        $usuarios->random($cantidadAdmins)->each(function ($usuario) {
            if (!Administrador::where('user_id', $usuario->id)->exists()) {
                Administrador::create(['user_id' => $usuario->id]);
                $this->command->info('Administrador asignado: ' . $usuario->id);
            }
        });
    }
}
