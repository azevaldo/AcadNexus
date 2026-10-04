# 🎓 AcadNexus — Plataforma de Gestão de Projetos Académicos

> 🚧 **Projeto em desenvolvimento**

O **AcadNexus** é uma plataforma web desenvolvida para apoiar a gestão, organização e acompanhamento de projetos académicos em instituições de ensino superior.

A plataforma procura centralizar num único sistema processos que normalmente podem ser realizados de forma dispersa, permitindo acompanhar estudantes, propostas, avaliações, orientações, etapas de desenvolvimento e histórico das atividades académicas.

---

## 📌 Estado do projeto

**Status:** 🚧 Em desenvolvimento

O sistema ainda está em fase de desenvolvimento. Algumas funcionalidades estão implementadas, enquanto outras estão previstas para versões futuras.

---

## 🎯 Objetivo

O principal objetivo do AcadNexus é disponibilizar uma plataforma para organizar e acompanhar os processos relacionados com projetos académicos.

A plataforma pretende:

* Centralizar informações académicas;
* Permitir submissão de propostas;
* Facilitar a avaliação das propostas;
* Manter o histórico das versões submetidas;
* Apoiar a relação entre estudantes, professores e coordenadores;
* Acompanhar o desenvolvimento dos projetos;
* Controlar diferentes etapas do processo;
* Registar avaliações e decisões;
* Facilitar a consulta do histórico académico;
* Reduzir a utilização de documentos físicos e processos dispersos.

---

## 🧩 Problema

Em muitos contextos académicos, o acompanhamento de projetos pode envolver documentos físicos, ficheiros enviados por diferentes meios, mensagens, planilhas e outros mecanismos separados.

Essa fragmentação pode dificultar:

* O acompanhamento do estudante;
* A organização dos documentos;
* O controlo das avaliações;
* A consulta do histórico;
* A comunicação entre os envolvidos;
* O acompanhamento das diferentes etapas;
* A identificação das alterações realizadas ao longo do processo.

O AcadNexus foi pensado para centralizar essas informações numa única aplicação web.

---

# 🔄 Funcionamento geral

O fluxo principal da plataforma pode ser representado da seguinte forma:

```text
Estudante
    ↓
Proposta
    ↓
Submissão
    ↓
Avaliação
    ↓
┌───────────────┐
│   Aprovada    │
│      ou       │
│   Melhorias   │
└───────────────┘
    ↓
Orientação
    ↓
Acompanhamento
    ↓
Etapas do projeto
    ↓
Avaliações
    ↓
Finalização
```

O sistema mantém o histórico das principais operações realizadas durante esse processo.

---

# 👥 Perfis de utilizador

## 👨‍💼 Administrador

Responsável pela administração geral da plataforma.

Pode realizar operações como:

* Gestão de utilizadores;
* Gestão da estrutura académica;
* Configuração do sistema;
* Gestão de cursos;
* Gestão de departamentos;
* Gestão de professores;
* Gestão de estudantes;
* Acompanhamento geral da plataforma.

---

## 👨‍🏫 Coordenador

Responsável pelo acompanhamento dos processos académicos dentro do seu contexto.

Pode:

* Criar períodos de submissão;
* Acompanhar estudantes;
* Avaliar propostas;
* Aprovar propostas;
* Rejeitar propostas;
* Solicitar melhorias;
* Acompanhar orientações;
* Consultar informações relacionadas ao seu departamento.

---

## 👨‍🏫 Professor

Pode participar no processo de orientação e acompanhamento dos estudantes.

Entre as funcionalidades estão:

* Consultar estudantes orientados;
* Receber solicitações de orientação;
* Aceitar ou recusar orientações;
* Acompanhar projetos;
* Avaliar determinadas etapas;
* Consultar informações dos projetos sob sua responsabilidade.

---

## 👨‍🎓 Estudante

É o utilizador responsável pela submissão e desenvolvimento do projeto.

Pode:

* Consultar períodos disponíveis;
* Submeter propostas;
* Consultar o estado das propostas;
* Submeter novas versões;
* Responder a solicitações de melhorias;
* Solicitar orientação;
* Acompanhar o projeto;
* Submeter documentos;
* Consultar avaliações;
* Acompanhar as etapas do projeto.

---

# ⚙️ Principais funcionalidades

## 🔐 Autenticação

O sistema possui mecanismos de autenticação para controlar o acesso à plataforma.

Inclui:

* Login;
* Logout;
* Controlo de acesso;
* Gestão de sessão;
* Proteção das áreas restritas.

---

## 🏢 Estrutura académica

Gestão da estrutura necessária para organizar os processos académicos.

Inclui:

* Departamentos;
* Cursos;
* Professores;
* Estudantes;
* Áreas científicas;
* Coordenadores.

---

## 📅 Períodos de submissão

Permite criar e gerir períodos durante os quais os estudantes podem participar nos processos de submissão.

É possível definir informações como:

* Título;
* Descrição;
* Curso;
* Prazo de submissão;
* Prazo para melhorias;
* Estudantes participantes.

---

## 📄 Gestão de propostas

A plataforma permite que os estudantes submetam propostas para avaliação.

Uma proposta possui informações próprias e pode ter diferentes versões ao longo do processo.

O sistema permite:

* Criar proposta;
* Submeter proposta;
* Consultar proposta;
* Alterar informações;
* Submeter novas versões;
* Consultar histórico;
* Acompanhar o estado da proposta.

---

## 🔢 Versionamento

Uma proposta pode possuir várias versões.

Exemplo:

```text
Versão 1
   ↓
Avaliação
   ↓
Solicitação de melhorias
   ↓
Versão 2
   ↓
Nova avaliação
   ↓
Aprovação
```

As versões anteriores permanecem no histórico para permitir o acompanhamento da evolução da proposta.

---

## 📝 Avaliações

Os responsáveis podem avaliar as propostas submetidas.

As decisões podem incluir:

* Aprovação;
* Rejeição;
* Solicitação de melhorias.

Quando forem solicitadas melhorias, deve existir uma justificação associada à decisão.

---

## 📋 Melhorias

Quando uma proposta necessita de alterações, o sistema permite registar os pontos que precisam de ser corrigidos.

O estudante pode posteriormente submeter uma nova versão da proposta.

---

## 👨‍🏫 Orientação

A plataforma permite gerir a relação entre estudantes e professores.

O processo pode envolver:

```text
Estudante
    ↓
Solicitação de orientação
    ↓
Professor
    ↓
Aceitar / Recusar
    ↓
Orientação
```

Também existe suporte para acompanhamento da orientação ao longo do projeto.

---

## 📊 Acompanhamento

Depois da aprovação de uma proposta, o processo pode passar para a fase de acompanhamento.

O acompanhamento permite:

* Associar estudante;
* Associar orientador;
* Registar etapas;
* Submeter documentos;
* Registar avaliações;
* Consultar progresso;
* Manter histórico.

---

# 📚 Etapas do projeto

O sistema foi estruturado para permitir o acompanhamento de diferentes etapas.

Entre elas:

1. Escolha do tema;
2. Desenvolvimento da proposta;
3. Fundamentação teórica;
4. Levantamento bibliográfico;
5. Metodologia;
6. Desenvolvimento;
7. Testes e resultados;
8. Conclusão;
9. Preparação para apresentação.

Cada etapa pode possuir submissões e avaliações próprias.

---

# 📤 Submissão de etapas

O estudante pode submeter documentos e informações relacionados com cada etapa.

O sistema mantém o histórico das submissões para facilitar o acompanhamento da evolução do projeto.

---

# ✅ Avaliação das etapas

As etapas podem ser avaliadas pelo responsável pelo acompanhamento.

Uma etapa pode ser:

* Aprovada;
* Devolvida para ajustes.

O estudante não deve avançar para determinadas etapas enquanto a etapa anterior não estiver devidamente aprovada.

---

# 📜 Histórico

O sistema procura preservar o histórico das principais operações realizadas.

Podem ser registados eventos como:

* Submissões;
* Alterações;
* Avaliações;
* Aprovações;
* Rejeições;
* Solicitações de melhorias;
* Aceitação de orientação;
* Recusa de orientação;
* Alterações relacionadas ao projeto.

---

# 🔐 Regras de negócio

Entre as principais regras definidas para o sistema:

* Apenas estudantes participantes de um determinado período podem realizar submissões;
* Toda decisão deve possuir uma justificação;
* Uma proposta pode possuir várias versões;
* Cada versão possui uma decisão correspondente;
* Quando são solicitadas melhorias, o estudante deve submeter uma nova versão;
* O orientador deve estar associado ao estudante antes do acompanhamento;
* A coorientação pode ser opcional;
* Uma proposta aprovada pode iniciar o acompanhamento;
* O estudante não deve avançar sem a aprovação da etapa anterior;
* O histórico das alterações deve ser preservado;
* As versões anteriores não devem ser eliminadas;
* Um professor pode aceitar ou recusar uma solicitação de orientação;
* O coordenador deve atuar dentro do contexto académico sob sua responsabilidade.

---

# 🏗️ Arquitetura

O projeto utiliza uma arquitetura baseada no padrão **MVC (Model-View-Controller)** disponibilizado pelo Laravel.

```text
┌─────────────────────────────┐
│          Utilizador          │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│          Interface           │
│       Blade + Tailwind       │
│         JavaScript           │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│        Controllers           │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│           Models             │
│          Laravel             │
└──────────────┬──────────────┘
               │
               ▼
┌─────────────────────────────┐
│           MySQL              │
└─────────────────────────────┘
```

Quando necessário, o sistema também pode comunicar com serviços externos através de **APIs REST**.

---

# 🛠️ Tecnologias utilizadas

O projeto foi desenvolvido utilizando as seguintes tecnologias:

| Tecnologia       | Utilização                                          |
| ---------------- | --------------------------------------------------- |
| **PHP**          | Linguagem principal do backend                      |
| **Laravel**      | Framework utilizado no desenvolvimento da aplicação |
| **MySQL**        | Base de dados relacional                            |
| **Blade**        | Construção das interfaces e templates               |
| **Tailwind CSS** | Estilização da interface                            |
| **JavaScript**   | Interações e funcionalidades no frontend            |
| **API REST**     | Comunicação com serviços e recursos externos        |

### Backend

* PHP
* Laravel

### Frontend

* Blade
* Tailwind CSS
* JavaScript

### Base de dados

* MySQL

### Comunicação

* API REST

---

# 📁 Estrutura do projeto

A estrutura segue o padrão de uma aplicação Laravel:

```text
acadnexus/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   │
│   └── Models/
│
├── bootstrap/
│
├── config/
│
├── database/
│   ├── migrations/
│   └── seeders/
│
├── public/
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       ├── layouts/
│       ├── components/
│       └── ...
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── storage/
│
├── tests/
│
├── .env.example
├── artisan
├── composer.json
└── README.md
```

---

# 💻 Requisitos

Antes de executar o projeto, é necessário ter instalado:

* PHP;
* Composer;
* MySQL;
* Node.js e NPM, caso sejam necessários para compilar os recursos do frontend;
* Git.

> As versões exatas utilizadas devem ser consultadas nos ficheiros `composer.json` e `package.json` do projeto.

---

# 🚀 Instalação

## 1. Clonar o projeto

```bash
git clone URL_DO_REPOSITORIO
```

Entrar na pasta:

```bash
cd acadnexus
```

---

## 2. Instalar as dependências do Laravel

```bash
composer install
```

---

## 3. Configurar o ficheiro `.env`

Copiar o ficheiro de configuração:

### Linux / macOS

```bash
cp .env.example .env
```

### Windows PowerShell

```powershell
Copy-Item .env.example .env
```

---

## 4. Gerar a chave da aplicação

```bash
php artisan key:generate
```

---

## 5. Criar a base de dados

No MySQL, criar uma base de dados:

```sql
CREATE DATABASE acadnexus;
```

---

## 6. Configurar a base de dados

No ficheiro `.env`, configurar os dados de acesso ao MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=acadnexus
DB_USERNAME=root
DB_PASSWORD=
```

Altere os valores conforme a configuração do seu ambiente.

---

## 7. Executar as migrations

```bash
php artisan migrate
```

Se o projeto possuir seeders:

```bash
php artisan db:seed
```

Ou:

```bash
php artisan migrate --seed
```

---

## 8. Criar o link de armazenamento

Caso a aplicação utilize ficheiros armazenados no `storage`:

```bash
php artisan storage:link
```

---

## 9. Limpar as configurações em cache

```bash
php artisan optimize:clear
```

---

## 10. Executar o projeto

```bash
php artisan serve
```

Por padrão, a aplicação ficará disponível em:

```text
http://127.0.0.1:8000
```

---

# 🎨 Frontend

A interface é construída utilizando:

* Blade;
* Tailwind CSS;
* JavaScript.

O Blade é responsável pela construção das páginas e integração com os dados fornecidos pelo Laravel.

O Tailwind CSS é utilizado para a estilização das interfaces.

O JavaScript é utilizado para funcionalidades e interações que necessitam de comportamento no lado do cliente.

---

# 🔌 API REST

O projeto possui integração através de **API REST** para comunicação com recursos externos quando necessário.

As rotas relacionadas à API ficam organizadas em:

```text
routes/api.php
```

A comunicação pode utilizar formatos como JSON para troca de dados.

Exemplo:

```http
GET /api/...
POST /api/...
PUT /api/...
DELETE /api/...
```

As rotas e endpoints efetivamente disponíveis devem ser consultados no código do projeto.

---

# 🔒 Segurança

A aplicação considera mecanismos de segurança como:

* Autenticação de utilizadores;
* Controlo de acesso;
* Validação de dados;
* Proteção das rotas;
* Proteção dos documentos;
* Validação de ficheiros;
* Integridade das relações da base de dados;
* Preservação do histórico das operações.

O sistema também procura limitar o acesso de cada perfil às funcionalidades correspondentes à sua responsabilidade.

---

# 📊 Relatórios

O sistema foi projetado para permitir a consulta de informações como:

* Estudantes por curso;
* Estudantes por departamento;
* Períodos ativos e encerrados;
* Propostas submetidas;
* Propostas aprovadas;
* Propostas rejeitadas;
* Propostas com melhorias solicitadas;
* Orientações ativas;
* Distribuição de orientadores;
* Projetos em acompanhamento;
* Etapas concluídas;
* Solicitações relacionadas aos projetos.

---

# 🖥️ Principais áreas do sistema

Entre as áreas previstas ou implementadas estão:

* Login;
* Dashboard;
* Gestão de utilizadores;
* Departamentos;
* Cursos;
* Professores;
* Estudantes;
* Áreas científicas;
* Períodos de submissão;
* Participantes;
* Submissão de propostas;
* Histórico de versões;
* Avaliação;
* Melhorias;
* Solicitação de orientação;
* Gestão de orientação;
* Acompanhamento;
* Etapas;
* Submissão de etapas;
* Avaliação de etapas;
* Relatórios.

---

# 🚧 Funcionalidades em desenvolvimento

Como o projeto ainda está em desenvolvimento, algumas funcionalidades podem estar incompletas ou ainda não implementadas.

Entre as funcionalidades previstas estão:

* Alteração formal de propostas;
* Avaliação da orientação;
* Melhorias na área de relatórios;
* Aperfeiçoamento do acompanhamento;
* Melhorias na interface;
* Novos mecanismos de controlo e auditoria.

---

# 🗺️ Roadmap

### Concluído / em desenvolvimento

* [x] Estrutura inicial do projeto;
* [x] Autenticação;
* [x] Gestão da estrutura académica;
* [x] Gestão de propostas;
* [x] Avaliação;
* [x] Versionamento;
* [x] Orientação;
* [x] Acompanhamento;
* [x] Gestão de etapas;
* [x] Interface web;
* [x] Integração com API REST.

### Próximos passos

* [ ] Finalizar funcionalidades pendentes;
* [ ] Melhorar a experiência do utilizador;
* [ ] Expandir os relatórios;
* [ ] Melhorar mecanismos de auditoria;
* [ ] Realizar testes adicionais;
* [ ] Melhorar documentação;
* [ ] Preparar uma versão mais estável do sistema.

---

# 🤝 Contribuição

O projeto encontra-se em desenvolvimento e contribuições para fins de **estudo, aprendizagem, investigação, testes e melhoria técnica** são bem-vindas.

Para contribuir:

```bash
git clone URL_DO_REPOSITORIO
```

Crie uma nova branch:

```bash
git checkout -b minha-alteracao
```

Faça as alterações necessárias e depois:

```bash
git add .
git commit -m "Descrição da alteração"
git push origin minha-alteracao
```

Posteriormente, pode ser aberta uma Pull Request para análise.

---

# 📚 Finalidade de utilização

Este projeto foi desenvolvido principalmente para fins de:

* Estudo;
* Aprendizagem;
* Investigação;
* Experimentação;
* Desenvolvimento de software;
* Demonstração técnica;
* Contribuições da comunidade.

### 🚫 Uso comercial

O código e os materiais deste projeto **não estão autorizados para utilização comercial** sem autorização prévia e expressa do autor.

Não é permitida, sem autorização:

* Comercialização do sistema;
* Venda do código;
* Utilização do projeto como produto comercial;
* Incorporação em produtos comerciais;
* Redistribuição comercial;
* Remoção dos créditos do autor;
* Apresentação do projeto como sendo de autoria de terceiros.

A disponibilização pública do código **não significa que o projeto esteja sob uma licença open source que permita uso comercial livre**.

Para utilização comercial ou outro tipo de utilização não contemplada neste README, deverá ser solicitada autorização prévia ao autor.

---

# ©️ Direitos autorais

**© 2026 Azevaldo Caluaco — Todos os direitos reservados.**

O projeto, o código-fonte, a documentação e os materiais relacionados são de autoria de **Azevaldo Caluaco**, salvo indicação expressa em contrário.

O projeto encontra-se em desenvolvimento e poderá sofrer alterações na estrutura, funcionalidades e tecnologias utilizadas.

---

# 👨‍💻 Autor

**Azevaldo Caluaco**

Desenvolvedor Full Stack com foco em desenvolvimento backend e aplicações web.

### Tecnologias

* PHP
* Laravel
* MySQL
* Blade
* JavaScript
* Tailwind CSS
* API REST

---

# 📌 Nota

O **AcadNexus** é um projeto em desenvolvimento. A documentação apresentada descreve a proposta, estrutura e funcionalidades previstas para a plataforma, podendo algumas funcionalidades ainda não estar disponíveis ou sofrer alterações durante o desenvolvimento.

---

> 🎓 **AcadNexus — Organização e acompanhamento de projetos académicos numa única plataforma.**
