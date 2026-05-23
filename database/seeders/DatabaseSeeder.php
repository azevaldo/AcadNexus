<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
          RoleSeeder::class,      // 1º Criar as regras
         AcademicoSeeder::class, // 2º Criar Departamentos e Cursos
        UserSeeder::class, 
         ItemMelhoriaSeeder::class  // 3º Criar Usuários e dar as roles
    ]);
      
    }
}
