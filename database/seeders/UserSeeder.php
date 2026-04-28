<?php
// database/seeders/UsuarioSeeder.php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador do Sistema
        $admin = User::create([
            'name' => 'Admin Global',
            'email' => 'admin@tcc.com',
            'password' => Hash::make('password'), // Altere em produção!
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Secretaria
        $secretaria = User::create([
            'name' => 'Secretaria Acadêmica',
            'email' => 'secretaria@tcc.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $secretaria->assignRole('secretaria');

        // Coordenador de Exemplo
        $coord = User::create([
            'name' => 'Coordenador DTI',
            'email' => 'coordenador@tcc.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $coord->assignRole('coordenador');
    }
}