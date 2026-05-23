<?php

namespace Database\Seeders;

use App\Models\ItemMelhoria;
use Illuminate\Database\Seeder;

class ItemMelhoriaSeeder extends Seeder
{
    public function run(): void
    {
        $itens = [
            ['titulo' => 'Formatação ABNT', 'descricao' => 'O trabalho não segue as normas de margens, fontes ou espaçamentos da ABNT.'],
            ['titulo' => 'Sumário Desatualizado', 'descricao' => 'A paginação ou os títulos do sumário não correspondem ao conteúdo atual.'],
            ['titulo' => 'Citações Incorretas', 'descricao' => 'As citações diretas ou indiretas não seguem o padrão Autor-Data.'],
            ['titulo' => 'Uso de Figuras', 'descricao' => 'Imagens sem fonte, sem legenda ou sem referência direta no corpo do texto.'],
            ['titulo' => 'Parágrafos Extensos', 'descricao' => 'Blocos de texto muito longos que dificultam a compreensão da ideia central.'],
            ['titulo' => 'Falta de Coesão', 'descricao' => 'Ausência de conectivos entre parágrafos, tornando a leitura fragmentada.'],
            ['titulo' => 'Título Inadequado', 'descricao' => 'O título está muito genérico ou não reflete o problema de pesquisa.'],
            ['titulo' => 'Problema de Pesquisa', 'descricao' => 'O problema não foi formulado como uma pergunta clara e objetiva.'],
            ['titulo' => 'Objetivo Geral Vago', 'descricao' => 'O objetivo principal não ataca diretamente a dúvida central do projeto.'],
            ['titulo' => 'Verbos Inadequados', 'descricao' => 'Uso de verbos que não expressam ações mensuráveis nos objetivos.'],
            ['titulo' => 'Justificativa Fraca', 'descricao' => 'Não demonstra a relevância social, acadêmica ou técnica do estudo.'],
            ['titulo' => 'Delimitação do Tema', 'descricao' => 'O tema está abrangente demais para o tempo de execução previsto.'],
            ['titulo' => 'Viabilidade Técnica', 'descricao' => 'O projeto exige recursos ou acessos que não ficaram claros na proposta.'],
            ['titulo' => 'Tipo de Pesquisa', 'descricao' => 'Não define se a pesquisa é qualitativa, quantitativa ou exploratória.'],
            ['titulo' => 'Delineamento Vago', 'descricao' => 'Falta detalhar se é estudo de caso, pesquisa bibliográfica ou experimental.'],
            ['titulo' => 'Amostra Indefinida', 'descricao' => 'Não define os critérios de seleção dos sujeitos ou objetos de estudo.'],
            ['titulo' => 'Coleta de Dados', 'descricao' => 'Não descreve os instrumentos (questionários, entrevistas) que serão usados.'],
            ['titulo' => 'Análise de Dados', 'descricao' => 'Falta explicar como os dados serão interpretados após a coleta.'],
            ['titulo' => 'Cronograma Irreal', 'descricao' => 'As datas propostas são insuficientes para as etapas descritas.'],
            ['titulo' => 'Referências Antigas', 'descricao' => 'A bibliografia está defasada para um tema que exige atualidade.'],
            ['titulo' => 'Citação sem Referência', 'descricao' => 'Autor citado no texto não foi encontrado na lista de referências.'],
            ['titulo' => 'Referência sem Citação', 'descricao' => 'Obra listada nas referências não aparece citada em nenhuma parte do texto.'],
            ['titulo' => 'Fontes Não Confiáveis', 'descricao' => 'Uso excessivo de sites sem credibilidade acadêmica ou científica.'],
            ['titulo' => 'Erros Gramaticais', 'descricao' => 'Presença de erros de concordância, pontuação ou ortografia básica.'],
            ['titulo' => 'Linguagem Coloquial', 'descricao' => 'O texto utiliza expressões informais inadequadas para um trabalho acadêmico.'],
            ['titulo' => 'Falta de Fundamentação', 'descricao' => 'Afirmações importantes feitas sem o suporte de autores da área.'],
            ['titulo' => 'Referencial Pobre', 'descricao' => 'Pouca profundidade teórica na discussão dos conceitos principais.'],
            ['titulo' => 'Hipóteses Ausentes', 'descricao' => 'Falta uma resposta provisória ou suposição para o problema de pesquisa.'],
            ['titulo' => 'Siglas Não Explicadas', 'descricao' => 'Uso de siglas sem a apresentação do significado por extenso.'],
            ['titulo' => 'Metodologia Incompleta', 'descricao' => 'A seção de métodos não permite a replicação do estudo por terceiros.'],
            ['titulo' => 'Resultados Esperados', 'descricao' => 'Falta clareza sobre o que se pretende alcançar ao fim da pesquisa.'],
            ['titulo' => 'Introdução Confusa', 'descricao' => 'A introdução não contextualiza o tema de forma lógica e progressiva.'],
            ['titulo' => 'Falta de Originalidade', 'descricao' => 'O projeto é puramente repetitivo e não propõe uma nova visão.'],
            ['titulo' => 'Numeração de Seções', 'descricao' => 'A hierarquia dos títulos e subtítulos está desorganizada.'],
            ['titulo' => 'Tradução Automática', 'descricao' => 'Trechos que parecem traduzidos sem revisão, perdendo a coesão.'],
            ['titulo' => 'Notas de Rodapé', 'descricao' => 'Uso excessivo ou incorreto de notas de rodapé para citações.'],
            ['titulo' => 'Links Quebrados', 'descricao' => 'Referências de sites com URLs que não funcionam ou estão incompletas.'],
            ['titulo' => 'Estrutura de Tópicos', 'descricao' => 'Os tópicos do projeto não seguem a ordem lógica padrão da instituição.'],
            ['titulo' => 'Objetivos Específicos', 'descricao' => 'Confundiu etapas de trabalho com objetivos de conhecimento.'],
            ['titulo' => 'Anexos/Apensos', 'descricao' => 'Documentos complementares citados mas não incluídos no final do arquivo.']
        ];

        foreach ($itens as $item) {
            ItemMelhoria::create($item);
        }
    }
}