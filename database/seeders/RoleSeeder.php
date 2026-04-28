<?php

 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Limpar cache de permissões (importante no Spatie)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. DEFINIR PERMISSÕES
        $permissions = [
            'gerenciar usuarios',
            'configurar sistema',
            'gerenciar cursos',
            'avaliar fases',
            'submeter trabalho',
            'visualizar progresso',
            'emitir certificados',
            'vincular orientadores',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. CRIAR ROLES E ATRIBUIR PERMISSÕES

        // ADMIN: Poder total
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // COORDENADOR: Gerencia cursos e vínculos
        $coordenador = Role::create(['name' => 'coordenador']);
        $coordenador->givePermissionTo([
            'gerenciar cursos',
            'vincular orientadores',
            'visualizar progresso'
        ]);

        // SECRETARIA: Parte burocrática e usuários
        $secretaria = Role::create(['name' => 'secretaria']);
        $secretaria->givePermissionTo([
            'gerenciar usuarios',
            'emitir certificados',
            'visualizar progresso'
        ]);

        // PROFESSOR: Avaliação
        $professor = Role::create(['name' => 'professor']);
        $professor->givePermissionTo([
            'avaliar fases',
            'visualizar progresso'
        ]);

        // ESTUDANTE: Submissão
        $estudante = Role::create(['name' => 'estudante']);
        $estudante->givePermissionTo([
            'submeter trabalho',
            'visualizar progresso'
        ]);
    }
}