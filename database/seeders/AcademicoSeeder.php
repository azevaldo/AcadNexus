<?php
// database/seeders/AcademicoSeeder.php
namespace Database\Seeders;

use App\Models\Departamento;
use App\Models\Curso;
use Illuminate\Database\Seeder;

class AcademicoSeeder extends Seeder
{
    public function run(): void
    {
        $deps = [
            ['nome' => 'Tecnologia da Informação'],
            ['nome' => 'Engenharia e Produção'],
            ['nome' => 'Ciências Humanas'],
        ];

        foreach ($deps as $d) {
            $depto = Departamento::create($d);

  
            }
        Curso::create(['nome' => 'Análise e Desenvolvimento de Sistemas', 'departamento_id' => 1]);
                Curso::create(['nome' => 'Ciência da Computação', 'departamento_id' =>2]);
                Curso::create(['nome' => 'Sistemas de Informação', 'departamento_id' => 3]);
    }
}