<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Correta importação da classe Hash
use App\Models\User;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Excluir todos os usuários existentes
        User::truncate();

        // Criar o novo usuário
        User::create([
            'name' => 'Usuário Master',
            'email' => 'rafaeldill16@gmail.com',
            'password' => Hash::make('rafa@#234f3'), // Criptografar a senha
        ]);
    }
}
