<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SugestaoTemaIa;
use App\Models\FavoritoTemaIa;
use App\Models\AreaCientifica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IaController extends Controller
{
public function index()
{
    if (!Auth::user()->estudante) {
        return redirect()->back()
            ->with('error', 'Apenas estudantes podem aceder a esta funcionalidade.');
    }

    $estudanteId = Auth::user()->estudante->id;

    // Sugestões paginadas
    $sugestoes = SugestaoTemaIa::where('estudante_id', $estudanteId)
        ->latest()
        ->paginate(6, ['*'], 'sugestoes_page');

    // Favoritos paginados
    $favoritos = FavoritoTemaIa::with('sugestao')
        ->where('estudante_id', $estudanteId)
        ->latest()
        ->paginate(4, ['*'], 'favoritos_page');

    return view(
        'app.private.dashboard.estudantes.ia.index',
        compact('favoritos', 'sugestoes')
    );
}

public function gerar(Request $request)
{
    $request->validate([
        'area_nome' => [
            'required',
            'string',
            'min:3',
            'max:255'
        ],

        'tipo_projeto' => [
            'required',
            'in:teorico,pratico'
        ],

        'disciplinas' => [
            'required',
            'string',
            'min:5',
            'max:500'
        ],

        'competencias' => [
            'nullable',
            'string',
            'max:500'
        ],
    ]);

    try {

        /*
        |--------------------------------------------------------------------------
        | Limpeza de dados
        |--------------------------------------------------------------------------
        */

        $area = trim($request->area_nome);

        $disciplinas = trim($request->disciplinas);

        $competencias = trim($request->competencias ?? '');

        /*
        |--------------------------------------------------------------------------
        | Validação Inteligente Inicial
        |--------------------------------------------------------------------------
        */

        $palavrasInvalidas = [
            'aaa',
            'bbb',
            'teste',
            '123',
            'abc',
            'xxxx',
            'nada'
        ];

        foreach ($palavrasInvalidas as $palavra) {

            if (
                str_contains(strtolower($area), $palavra) ||
                str_contains(strtolower($disciplinas), $palavra)
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Os dados fornecidos parecem inválidos ou sem contexto acadêmico.'
                    );
            }
        }

        if (str_word_count($disciplinas) < 2) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Informe disciplinas mais detalhadas e coerentes.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | API KEY
        |--------------------------------------------------------------------------
        */

        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A chave da inteligência artificial não foi configurada.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Estudante
        |--------------------------------------------------------------------------
        */

        $estudante = Auth::user()->estudante;

        if (!$estudante) {

            return back()
                ->with(
                    'error',
                    'Apenas estudantes podem usar esta funcionalidade.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prompt Inteligente
        |--------------------------------------------------------------------------
        */

        $prompt = "

        Você é um orientador universitário extremamente rigoroso.

        Analise profundamente os dados enviados pelo estudante.

        Verifique:
        - coerência textual;
        - qualidade da escrita;
        - português correto;
        - clareza científica;
        - relação entre área, disciplinas e competências;
        - relevância acadêmica;
        - sentido lógico;
        - maturidade das ideias;
        - viabilidade dos temas.

        REGRAS IMPORTANTES:

        1. Se os dados forem vagos, incoerentes,
        aleatórios, fracos ou sem contexto científico,
        NÃO gere temas.

        2. Nesse caso devolva:

        {
          \"status\": \"erro\",
          \"mensagem\": \"explicação clara do problema\"
        }

        3. Se os dados forem válidos:

        - gere EXATAMENTE 3 temas;
        - títulos profissionais;
        - linguagem acadêmica;
        - temas modernos;
        - coerentes;
        - inovadores;
        - sem repetir ideias;
        - descrição objetiva.

        4. Responda APENAS JSON VÁLIDO.
        NÃO escreva textos antes do JSON.

        Dados do estudante:

        Área científica:
        {$area}

        Tipo do projeto:
        {$request->tipo_projeto}

        Disciplinas:
        {$disciplinas}

        Competências:
        {$competencias}

        Modelo obrigatório:

        {
          \"status\": \"sucesso\",
          \"temas\": [
            {
              \"titulo\": \"Título do tema\",
              \"descricao\": \"Descrição do tema\"
            }
          ]
        }

        ";

        /*
        |--------------------------------------------------------------------------
        | Chamada API
        |--------------------------------------------------------------------------
        */

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])
        ->timeout(60)
        ->post(
            'https://api.groq.com/openai/v1/chat/completions',
            [
                'model' => 'llama-3.3-70b-versatile',

                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],

                'temperature' => 0.2,

                'max_tokens' => 1500,

                'response_format' => [
                    'type' => 'json_object'
                ]
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Erro da API
        |--------------------------------------------------------------------------
        */

        if ($response->failed()) {

            Log::error('ERRO GROQ', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Falha na comunicação com a inteligência artificial.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Resposta da IA
        |--------------------------------------------------------------------------
        */

        $data = $response->json();

        Log::info('RESPOSTA GROQ', $data);

        $content = $data['choices'][0]['message']['content'] ?? null;

        if (!$content) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A IA não conseguiu gerar resposta.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Limpeza da resposta
        |--------------------------------------------------------------------------
        */

        $content = str_replace([
            '```json',
            '```'
        ], '', $content);

        $content = trim($content);

        /*
        |--------------------------------------------------------------------------
        | Extrair JSON
        |--------------------------------------------------------------------------
        */

        $inicio = strpos($content, '{');

        $fim = strrpos($content, '}');

        if ($inicio === false || $fim === false) {

            Log::error('JSON NÃO ENCONTRADO', [
                'conteudo' => $content
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A IA devolveu uma resposta inválida.'
                );
        }

        $jsonString = substr(
            $content,
            $inicio,
            $fim - $inicio + 1
        );

        /*
        |--------------------------------------------------------------------------
        | Converter JSON
        |--------------------------------------------------------------------------
        */

        $resultado = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {

            Log::error('JSON INVÁLIDO', [
                'erro' => json_last_error_msg(),
                'json_extraido' => $jsonString,
                'conteudo_original' => $content
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A IA devolveu um formato inválido.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Verifica Estrutura
        |--------------------------------------------------------------------------
        */

        if (!isset($resultado['status'])) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A resposta da IA está incompleta.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | IA Detectou Problemas
        |--------------------------------------------------------------------------
        */

        if ($resultado['status'] === 'erro') {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $resultado['mensagem']
                    ?? 'Os dados informados não possuem coerência acadêmica.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Verifica Temas
        |--------------------------------------------------------------------------
        */

        if (
            !isset($resultado['temas']) ||
            !is_array($resultado['temas']) ||
            count($resultado['temas']) === 0
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A IA não conseguiu gerar temas válidos.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Área Científica
        |--------------------------------------------------------------------------
        */

        $areaModel = AreaCientifica::firstOrCreate([
            'nome' => $area
        ]);

        /*
        |--------------------------------------------------------------------------
        | Guardar Temas
        |--------------------------------------------------------------------------
        */

        foreach ($resultado['temas'] as $tema) {

            if (
                !isset($tema['titulo']) ||
                !isset($tema['descricao'])
            ) {
                continue;
            }

            SugestaoTemaIa::create([

                'estudante_id' => $estudante->id,

                'area_id' => $areaModel->id,

                'disciplinas' => array_map(
                    'trim',
                    explode(',', $disciplinas)
                ),

                'competencias' => $competencias
                    ? array_map(
                        'trim',
                        explode(',', $competencias)
                    )
                    : [],

                'tipo_projeto' => $request->tipo_projeto,

                'titulo' => trim($tema['titulo']),

                'descricao' => trim($tema['descricao']),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Sucesso
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('estudante.ia.index')
            ->with(
                'success',
                'Os temas foram gerados com sucesso!'
            );

    } catch (\Throwable $e) {

        Log::error('ERRO CRÍTICO IA', [

            'mensagem' => $e->getMessage(),

            'linha' => $e->getLine(),

            'arquivo' => $e->getFile()
        ]);

        return back()
            ->withInput()
            ->with(
                'error',
                'Ocorreu uma falha inesperada ao gerar os temas.'
            );
    }
}
 public function gerar2(Request $request)
{
    $request->validate([
        'area_nome'    => [
            'required',
            'string',
            'min:3',
            'max:255'
        ],

        'tipo_projeto' => 'required|in:teorico,pratico',

        'disciplinas'  => [
            'required',
            'string',
            'min:5',
            'max:500'
        ],

        'competencias' => [
            'nullable',
            'string',
            'max:500'
        ],
    ]);

    try {

        /*
        |--------------------------------------------------------------------------
        | Validações Inteligentes Antes da IA
        |--------------------------------------------------------------------------
        */

        $area = trim($request->area_nome);
        $disciplinas = trim($request->disciplinas);
        $competencias = trim($request->competencias ?? '');

        // Evita entradas sem sentido
        $palavrasInvalidas = [
            'aaa',
            'bbb',
            'teste',
            '123',
            'xxxx',
            'nada',
            'abc'
        ];

        foreach ($palavrasInvalidas as $palavra) {

            if (
                str_contains(strtolower($area), $palavra) ||
                str_contains(strtolower($disciplinas), $palavra)
            ) {

                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Os dados informados parecem inválidos ou sem contexto acadêmico.'
                    );
            }
        }

        // Verifica tamanho mínimo real
        if (str_word_count($disciplinas) < 1) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Informe disciplinas mais detalhadas e coerentes.'
                );
        }

        if ($competencias && str_word_count($competencias) < 1) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'As competências técnicas parecem inválidas.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | API KEY
        |--------------------------------------------------------------------------
        */

        $apiKey = env('GROQ_API_KEY');

        if (!$apiKey) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A chave da IA não foi configurada.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Estudante
        |--------------------------------------------------------------------------
        */

        $estudante = Auth::user()->estudante;

        if (!$estudante) {

            return back()
                ->with(
                    'error',
                    'Apenas estudantes podem usar esta funcionalidade.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prompt Inteligente
        |--------------------------------------------------------------------------
        */

        $prompt = "

        Você é um orientador universitário extremamente rigoroso.

        Sua função é analisar cuidadosamente os dados do estudante.

        Analise:
        - coerência textual;
        - qualidade da escrita;
        - clareza acadêmica;
        - relação entre área, disciplinas e competências;
        - contexto científico;
        - relevância acadêmica;
        - se existe lógica entre as informações.

        REGRAS IMPORTANTES:

        1. Se os dados forem fracos, incoerentes, aleatórios,
        sem contexto acadêmico ou mal escritos,
        NÃO gere temas.

        2. Nesse caso devolva:
        {
          \"status\": \"erro\",
          \"mensagem\": \"explicação detalhada do problema\"
        }

        3. Se os dados forem válidos:
        - gere EXATAMENTE 3 temas;
        - títulos profissionais;
        - linguagem acadêmica;
        - temas modernos;
        - temas coerentes;
        - descrição clara;
        - português correto;
        - sem repetir ideias.

        4. O JSON deve ser 100% válido.

        Dados do estudante:

        Área científica:
        {$area}

        Tipo do projeto:
        {$request->tipo_projeto}

        Disciplinas:
        {$disciplinas}

        Competências:
        {$competencias}

        Modelo obrigatório de sucesso:

        {
          \"status\": \"sucesso\",
          \"temas\": [
            {
              \"titulo\": \"...\",
              \"descricao\": \"...\"
            }
          ]
        }

        ";

        /*
        |--------------------------------------------------------------------------
        | Chamada API
        |--------------------------------------------------------------------------
        */

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])
        ->timeout(60)
        ->post(
            'https://api.groq.com/openai/v1/chat/completions',
            [
                'model' => 'llama-3.3-70b-versatile',

                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],

                'temperature' => 0.2,

                'max_tokens' => 1500,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Erro API
        |--------------------------------------------------------------------------
        */

        if ($response->failed()) {

            Log::error('ERRO GROQ', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Falha na comunicação com a inteligência artificial.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Resposta
        |--------------------------------------------------------------------------
        */

        $data = $response->json();

        Log::info('RESPOSTA GROQ', $data);

        $content = $data['choices'][0]['message']['content'] ?? null;

        if (!$content) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A IA não conseguiu gerar resposta.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Limpeza
        |--------------------------------------------------------------------------
        */

        $content = str_replace([
            '```json',
            '```'
        ], '', $content);

        $content = trim($content);

        /*
        |--------------------------------------------------------------------------
        | JSON
        |--------------------------------------------------------------------------
        */

        $resultado = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {

            Log::error('JSON INVÁLIDO', [
                'erro' => json_last_error_msg(),
                'conteudo' => $content
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A IA devolveu dados inválidos.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Verifica Estrutura
        |--------------------------------------------------------------------------
        */

        if (!isset($resultado['status'])) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Estrutura de resposta inválida.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | IA Detectou Problemas
        |--------------------------------------------------------------------------
        */

        if ($resultado['status'] === 'erro') {

            return back()
                ->withInput()
                ->with(
                    'error',
                    $resultado['mensagem'] ??
                    'Os dados enviados não possuem coerência acadêmica.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Temas
        |--------------------------------------------------------------------------
        */

        if (
            !isset($resultado['temas']) ||
            !is_array($resultado['temas'])
        ) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'A IA não conseguiu estruturar os temas.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Área
        |--------------------------------------------------------------------------
        */

        $areaModel = AreaCientifica::firstOrCreate([
            'nome' => $area
        ]);

        /*
        |--------------------------------------------------------------------------
        | Guardar Temas
        |--------------------------------------------------------------------------
        */

        foreach ($resultado['temas'] as $tema) {

            if (
                !isset($tema['titulo']) ||
                !isset($tema['descricao'])
            ) {
                continue;
            }

            SugestaoTemaIa::create([

                'estudante_id' => $estudante->id,

                'area_id' => $areaModel->id,

                'disciplinas' => array_map(
                    'trim',
                    explode(',', $disciplinas)
                ),

                'competencias' => $competencias
                    ? array_map(
                        'trim',
                        explode(',', $competencias)
                    )
                    : [],

                'tipo_projeto' => $request->tipo_projeto,

                'titulo' => trim($tema['titulo']),

                'descricao' => trim($tema['descricao']),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Sucesso
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('estudante.ia.index')
            ->with(
                'success',
                'Os temas foram gerados com sucesso!'
            );

    } catch (\Throwable $e) {

        Log::error('ERRO CRÍTICO IA', [

            'mensagem' => $e->getMessage(),

            'linha' => $e->getLine(),

            'arquivo' => $e->getFile(),
        ]);

        return back()
            ->withInput()
            ->with(
                'error',
                'Ocorreu uma falha inesperada ao gerar os temas.'
            );
    }
}

    public function favoritar($id)
    {
        FavoritoTemaIa::firstOrCreate([
            'estudante_id' => Auth::user()->estudante->id,
            'sugestao_id'  => $id
        ]);

        return back()->with('success', 'Tema guardado nos favoritos!');
    }

    public function removerFavorito($id)
    {
        FavoritoTemaIa::where('estudante_id', Auth::user()->estudante->id)
            ->where('id', $id)
            ->delete();

        return back()->with('info', 'Removido dos favoritos.');
    }
}