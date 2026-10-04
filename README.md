# 🎓 AcadNexus — Plataforma de Gestão de Projetos Académicos

> 🚧 **Projeto em desenvolvimento**

O **AcadNexus** é uma plataforma web desenvolvida para apoiar a gestão, organização e acompanhamento de projetos académicos em instituições de ensino superior.

A plataforma centraliza diferentes processos académicos, permitindo gerir propostas, submissões, avaliações, versões, orientação, acompanhamento e etapas de desenvolvimento dos projetos.

---

## 📌 Estado do projeto

**Status:** 🚧 Em desenvolvimento

O AcadNexus ainda está em desenvolvimento. Algumas funcionalidades já estão implementadas e outras encontram-se em desenvolvimento ou previstas para versões futuras.

---

## 🎯 Objetivo

O objetivo do AcadNexus é disponibilizar uma plataforma centralizada para organizar e acompanhar projetos académicos desde a submissão de uma proposta até às diferentes etapas do seu desenvolvimento.

A plataforma procura:

* Centralizar informações académicas;
* Digitalizar o processo de submissão;
* Facilitar a avaliação de propostas;
* Controlar diferentes versões de uma proposta;
* Registar decisões e melhorias;
* Facilitar a orientação dos estudantes;
* Acompanhar o desenvolvimento dos projetos;
* Controlar as diferentes etapas;
* Registar avaliações;
* Manter o histórico das atividades;
* Facilitar a comunicação entre estudantes, professores e coordenadores.

---

# 🧩 Problema

O acompanhamento de projetos académicos pode envolver documentos físicos, ficheiros, mensagens, planilhas e diferentes meios de comunicação.

Quando essas informações estão distribuídas por diferentes locais, torna-se mais difícil:

* Acompanhar o progresso dos estudantes;
* Organizar documentos;
* Controlar avaliações;
* Consultar versões anteriores;
* Acompanhar orientações;
* Manter um histórico das decisões;
* Identificar o estado atual de cada projeto.

O AcadNexus procura resolver esse problema através da centralização dessas informações numa única aplicação web.

---

# 🔄 Funcionamento geral

O processo principal da plataforma pode ser representado da seguinte forma:

```text
Estudante
    │
    ▼
Submissão da proposta
    │
    ▼
Avaliação
    │
    ├──────────────► Rejeitada
    │
    ├──────────────► Melhorias
    │                    │
    │                    ▼
    │               Nova versão
    │                    │
    │                    └──────► Nova avaliação
    │
    ▼
Aprovada
    │
    ▼
Orientação
    │
    ▼
Acompanhamento
    │
    ▼
Etapas do projeto
    │
    ▼
Avaliações
    │
    ▼
Finalização
```

---

# 👥 Perfis de utilizador

## 👨‍💼 Administrador

Possui acesso às principais funcionalidades administrativas da plataforma.

Pode gerir:

* Utilizadores;
* Departamentos;
* Cursos;
* Professores;
* Estudantes;
* Áreas científicas;
* Coordenadores;
* Configurações do sistema;
* Informações gerais da plataforma.

---

## 👨‍🏫 Coordenador

Responsável pelo acompanhamento dos processos académicos dentro do seu contexto.

Pode:

* Criar períodos de submissão;
* Gerir participantes;
* Avaliar propostas;
* Aprovar propostas;
* Rejeitar propostas;
* Solicitar melhorias;
* Acompanhar estudantes;
* Consultar projetos;
* Acompanhar orientações.

---

## 👨‍🏫 Professor

Participa principalmente no processo de orientação e acompanhamento.

Pode:

* Consultar solicitações de orientação;
* Aceitar ou recusar orientação;
* Consultar estudantes orientados;
* Acompanhar projetos;
* Avaliar determinadas etapas;
* Consultar informações relacionadas aos projetos sob sua responsabilidade.

---

## 👨‍🎓 Estudante

É responsável pela submissão e desenvolvimento do projeto.

Pode:

* Consultar períodos disponíveis;
* Participar de períodos;
* Submeter propostas;
* Consultar o estado das propostas;
* Submeter novas versões;
* Consultar melhorias solicitadas;
* Solicitar orientação;
* Acompanhar o projeto;
* Submeter documentos;
* Consultar avaliações;
* Acompanhar as diferentes etapas.

---

# ⚙️ Principais funcionalidades

## 🔐 Autenticação

O sistema possui autenticação para controlar o acesso dos utilizadores.

Inclui:

* Login;
* Logout;
* Gestão de sessão;
* Proteção de rotas;
* Controlo de acesso;
* Validação de credenciais.

---

## 🏢 Gestão académica

A plataforma permite gerir a estrutura académica necessária para o funcionamento do sistema.

Inclui:

* Departamentos;
* Cursos;
* Professores;
* Estudantes;
* Áreas científicas;
* Coordenadores.

---

## 📅 Gestão de períodos

Permite criar períodos nos quais determinados estudantes podem participar no processo de submissão.

Um período pode possuir:

* Título;
* Descrição;
* Curso;
* Prazo de submissão;
* Prazo para melhorias;
* Estudantes participantes.

---

## 📄 Gestão de propostas

Os estudantes podem submeter propostas para avaliação.

O sistema permite:

* Criar propostas;
* Submeter propostas;
* Consultar propostas;
* Consultar o estado;
* Alterar informações;
* Submeter novas versões;
* Consultar o histórico;
* Acompanhar decisões.

---

## 🔢 Versionamento

Uma proposta pode possuir várias versões.

```text
Versão 1
   │
   ▼
Avaliação
   │
   ▼
Melhorias solicitadas
   │
   ▼
Versão 2
   │
   ▼
Nova avaliação
   │
   ▼
Aprovação
```

As versões anteriores são mantidas para preservar o histórico da proposta.

---

## 📝 Avaliações

As propostas podem ser avaliadas pelos responsáveis.

As decisões podem ser:

* Aprovada;
* Rejeitada;
* Melhorias solicitadas.

As decisões devem possuir uma justificação.

---

## 📋 Gestão de melhorias

Quando uma proposta necessita de alterações, o responsável pode registar os pontos que devem ser corrigidos.

O estudante poderá posteriormente apresentar uma nova versão.

---

## 👨‍🏫 Orientação

O sistema permite gerir solicitações e associações entre estudantes e professores.

```text
Estudante
    │
    ▼
Solicitação de orientação
    │
    ▼
Professor
    │
    ├──► Aceitar
    │
    └──► Recusar
```

---

## 📊 Acompanhamento

Após a aprovação de uma proposta, o projeto pode entrar na fase de acompanhamento.

O sistema permite:

* Associar estudante;
* Associar orientador;
* Registar etapas;
* Receber submissões;
* Registar avaliações;
* Consultar progresso;
* Preservar o histórico.

---

# 📚 Etapas do projeto

O sistema foi estruturado para acompanhar diferentes etapas do desenvolvimento de um projeto académico.

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

Cada etapa pode possuir submissões e avaliações.

---

# 📤 Submissão de etapas

O estudante pode enviar documentos e informações relacionados com cada etapa.

O sistema mantém o histórico das submissões realizadas.

---

# ✅ Avaliação das etapas

As etapas podem ser avaliadas pelo responsável pelo acompanhamento.

Uma etapa pode ser:

* Aprovada;
* Devolvida para ajustes.

O avanço para determinadas etapas depende da aprovação da etapa anterior.

---

# 📜 Histórico

O sistema procura preservar o histórico das principais operações realizadas.

Entre os eventos registados podem estar:

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

Entre as principais regras:

* Apenas estudantes participantes de um período podem realizar submissões;
* Toda decisão deve possuir uma justificação;
* Uma proposta pode possuir várias versões;
* Cada versão possui uma decisão;
* Uma nova versão pode ser submetida quando forem solicitadas melhorias;
* O orientador deve estar associado ao estudante antes do acompanhamento;
* A coorientação pode ser opcional;
* Uma proposta aprovada pode iniciar o acompanhamento;
* O estudante não deve avançar sem a aprovação da etapa anterior;
* O histórico deve ser preservado;
* Versões anteriores não devem ser eliminadas;
* Um professor pode aceitar ou recusar uma solicitação de orientação;
* O coordenador atua dentro do contexto académico sob sua responsabilidade.

---

# 🏗️ Arquitetura

O projeto utiliza o padrão **MVC (Model-View-Controller)** do Laravel.

```text
┌──────────────────────────┐
│        Utilizador        │
└────────────┬─────────────┘
             │
             ▼
┌──────────────────────────┐
│        Interface         │
│ Blade + Tailwind CSS     │
│       JavaScript         │
└────────────┬─────────────┘
             │
             ▼
┌──────────────────────────┐
│       Controllers        │
└────────────┬─────────────┘
             │
             ▼
┌──────────────────────────┐
│         Models           │
│         Laravel          │
└────────────┬─────────────┘
             │
             ▼
┌──────────────────────────┐
│          MySQL           │
└──────────────────────────┘
```

A aplicação também possui suporte para comunicação através de **API REST**.

---

# 🛠️ Tecnologias utilizadas

| Tecnologia       | Utilização                            |
| ---------------- | ------------------------------------- |
| **PHP**          | Linguagem principal da aplicação      |
| **Laravel**      | Framework backend                     |
| **MySQL**        | Base de dados relacional              |
| **Blade**        | Templates e construção das interfaces |
| **Tailwind CSS** | Estilização da interface              |
| **JavaScript**   | Interações no frontend                |
| **API REST**     | Comunicação e integração de dados     |

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

> **Importante:** O projeto não utiliza Vite, NPM ou Node.js como requisito para instalação ou execução.

---

# 📁 Estrutura do projeto

A aplicação segue a estrutura padrão de um projeto Laravel:

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

Para executar o projeto, é necessário ter:

* PHP;
* Composer;
* MySQL;
* Git.

Não é necessário instalar:

* Node.js;
* NPM;
* Vite.

As versões utilizadas pelo projeto podem ser consultadas no ficheiro `composer.json`.

---

# 🚀 Instalação

## 1. Clonar o repositório

```bash
git clone URL_DO_REPOSITORIO
```

Entrar na pasta:

```bash
cd acadnexus
```

---

## 2. Instalar as dependências

Execute:

```bash
composer install
```

---

## 3. Configurar o ambiente

Copie o ficheiro `.env.example`:

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

Execute:

```bash
php artisan key:generate
```

---

# 🗄️ 5. Configurar o MySQL

Crie uma base de dados no MySQL:

```sql
CREATE DATABASE acadnexus;
```

Depois configure o ficheiro `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=acadnexus
DB_USERNAME=root
DB_PASSWORD=
```

Altere o utilizador e a palavra-passe conforme a configuração do seu MySQL.

---

# 🏗️ 6. Executar as migrations

Depois de configurar a base de dados:

```bash
php artisan migrate
```

As migrations irão criar as tabelas necessárias para o funcionamento da aplicação.

---

# 🌱 7. Executar o Database Seeder

O projeto possui um **Database Seeder** responsável por inserir dados iniciais necessários para utilização do sistema.

Depois das migrations, execute:

```bash
php artisan db:seed
```

Ou, se preferir executar migrations e seeders numa única operação:

```bash
php artisan migrate --seed
```

O seeder cria o utilizador administrador inicial.

### 👤 Credenciais do administrador

Após executar o seeder, pode entrar no sistema utilizando:

```text
E-mail: admin@tcc.com
Palavra-passe: password
```

O utilizador criado possui os seguintes dados:

```php
$admin{ 
    'email' => 'admin@tcc.com',
    'senha' => 'password'
    }
```

> ⚠️ **Importante:** Estas credenciais são destinadas ao ambiente inicial de desenvolvimento. Altere a palavra-passe antes de utilizar o sistema num ambiente de produção.

---

# 📦 8. Link do armazenamento

Caso a aplicação utilize ficheiros armazenados no `storage`, execute:

```bash
php artisan storage:link
```

---

# 🧹 9. Limpar a cache

Para garantir que as configurações estejam atualizadas:

```bash
php artisan optimize:clear
```

---

# ▶️ 10. Executar a aplicação

Inicie o servidor Laravel:

```bash
php artisan serve
```

A aplicação estará disponível normalmente em:

```text
http://127.0.0.1:8000
```

Acesse o endereço no navegador e utilize as credenciais do administrador apresentadas anteriormente.

---

# 🎨 Interface

A interface utiliza:

* **Blade** para construção das páginas;
* **Tailwind CSS** para estilização;
* **JavaScript** para interações.

O projeto não necessita de:

```text
npm install
npm run dev
vite
node
```

A aplicação pode ser executada diretamente pelo Laravel:

```bash
php artisan serve
```

---

# 🔌 API REST

O projeto possui comunicação através de **API REST**.

As rotas da API estão organizadas em:

```text
routes/api.php
```

Os métodos HTTP utilizados podem incluir:

```http
GET
POST
PUT
DELETE
```

Os endpoints disponíveis podem ser consultados diretamente no ficheiro:

```text
routes/api.php
```

---

# 🔒 Segurança

A aplicação considera mecanismos de segurança como:

* Autenticação;
* Controlo de acesso;
* Validação de dados;
* Proteção das rotas;
* Proteção dos documentos;
* Validação de ficheiros;
* Integridade da base de dados;
* Preservação do histórico.

O acesso às funcionalidades deve respeitar o perfil e o contexto académico do utilizador.

---

# 📊 Relatórios

A plataforma foi estruturada para disponibilizar informações como:

* Estudantes por curso;
* Estudantes por departamento;
* Períodos ativos e encerrados;
* Propostas submetidas;
* Propostas aprovadas;
* Propostas rejeitadas;
* Propostas com melhorias;
* Orientações ativas;
* Distribuição de orientadores;
* Projetos em acompanhamento;
* Etapas concluídas;
* Solicitações relacionadas aos projetos.

---

# 🖥️ Principais áreas

Entre as principais áreas do sistema estão:

* Login;
* Dashboard;
* Utilizadores;
* Departamentos;
* Cursos;
* Professores;
* Estudantes;
* Áreas científicas;
* Períodos de submissão;
* Participantes;
* Propostas;
* Histórico de versões;
* Avaliações;
* Melhorias;
* Orientação;
* Acompanhamento;
* Etapas;
* Submissão de etapas;
* Avaliação de etapas;
* Relatórios.

---

# 🚧 Funcionalidades em desenvolvimento

O projeto ainda está em desenvolvimento. Algumas funcionalidades podem estar incompletas ou ainda não implementadas.

Entre elas:

* Alteração formal de propostas;
* Avaliação da orientação;
* Melhorias nos relatórios;
* Aperfeiçoamento do acompanhamento;
* Melhorias na interface;
* Novos mecanismos de controlo e auditoria.

---

# 🗺️ Roadmap

### Implementado / em desenvolvimento

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
* [x] API REST.

### Próximos passos

* [ ] Finalizar funcionalidades pendentes;
* [ ] Melhorar a experiência do utilizador;
* [ ] Expandir os relatórios;
* [ ] Melhorar mecanismos de auditoria;
* [ ] Realizar testes adicionais;
* [ ] Melhorar a documentação;
* [ ] Preparar uma versão mais estável.

---

# 🤝 Contribuição

O projeto encontra-se em desenvolvimento.

Contribuições para fins de:

* Estudo;
* Aprendizagem;
* Investigação;
* Testes;
* Experimentação;
* Correção de problemas;
* Melhoria técnica;

são bem-vindas.

Para contribuir:

```bash
git clone URL_DO_REPOSITORIO
```

Criar uma branch:

```bash
git checkout -b minha-alteracao
```

Depois das alterações:

```bash
git add .
git commit -m "Descrição da alteração"
git push origin minha-alteracao
```

Posteriormente, pode ser aberta uma Pull Request.

---

# 📚 Finalidade de utilização

O AcadNexus é disponibilizado principalmente para fins de:

* Estudo;
* Aprendizagem;
* Investigação;
* Experimentação;
* Desenvolvimento de software;
* Demonstração técnica;
* Contribuição para melhoria do projeto.

## 🚫 Uso comercial

O projeto **não está autorizado para utilização comercial sem autorização prévia e expressa do autor**.

Sem autorização, não é permitida:

* Venda do sistema;
* Comercialização do código;
* Utilização como produto comercial;
* Incorporação em produtos comerciais;
* Redistribuição comercial;
* Remoção dos créditos do autor;
* Apresentação do projeto como sendo de autoria de terceiros.

A disponibilização pública do código não representa uma autorização para utilização comercial.

---

# ©️ Direitos autorais

**© 2026 Azevaldo Caluaco — Todos os direitos reservados.**

O código-fonte, documentação e materiais relacionados ao projeto são de autoria de **Azevaldo Caluaco**, salvo indicação expressa em contrário.

A utilização para estudo, aprendizagem, investigação, testes e contribuições é permitida dentro das condições apresentadas neste documento.

Qualquer utilização comercial necessita de autorização prévia e expressa do autor.

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

O **AcadNexus** é um projeto em desenvolvimento. A estrutura, funcionalidades e documentação podem sofrer alterações durante o processo de desenvolvimento.

---

> 🎓 **AcadNexus — Organização e acompanhamento de projetos académicos numa única plataforma.**
