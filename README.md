# Sistema de Gerenciamento de Usuários

Projeto desenvolvido utilizando Laravel, com autenticação de usuários e uma área administrativa para gerenciamento de contas.

## Funcionalidades

### Autenticação
- Login com e-mail e senha.
- Cadastro público de usuários.
- Validação dos dados informados.
- Limitação de tentativas de login.
- Logout com encerramento da sessão.
- Redirecionamento conforme o tipo de usuário.

### Área administrativa
- Acesso exclusivo para administradores.
- Listagem de usuários cadastrados.
- Edição dos dados dos usuários.
- Atualização das informações de perfil.
- Exclusão de usuários.
- Proteção contra a exclusão da própria conta administrativa.

### Segurança
- Proteção das rotas administrativas por middleware.
- Validação de permissões no servidor.
- Proteção CSRF nos formulários.
- Senhas armazenadas com hash.
- Regeneração da sessão após o login.
- Bloqueio de acesso administrativo para usuários comuns.
- Limite de 5 tentativas de login por e-mail e endereço IP, durante 60 segundos.

## Tecnologias utilizadas

- PHP
- Laravel
- SQLite
- Blade
- HTML
- CSS
- JavaScript
- Vite

## Como executar o projeto

### 1. Clonar o repositório

```bash
git clone https://github.com/ritamacedk/aula-alfa-laravel.git
cd aula-alfa-laravel
```

### 2. Instalar as dependências

```bash
composer install
npm install
```

### 3. Configurar o ambiente

Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente.

No Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

### 4. Configurar o banco de dados

O projeto utiliza SQLite.

Crie o arquivo `database/database.sqlite`, caso ainda não exista, e execute:

```bash
php artisan migrate
```

### 5. Compilar os arquivos front-end

```bash
npm run build
```

### 6. Iniciar o servidor

```bash
php artisan serve
```

A aplicação estará disponível em:

`http://127.0.0.1:8000`

## Primeiro administrador

O cadastro público cria usuários comuns. A criação do primeiro administrador deve ser realizada localmente, por meio de uma configuração administrativa no banco de dados.

O usuário deve possuir um perfil com o tipo `admin` para acessar a área administrativa.

## Observações

- Usuários comuns podem realizar cadastro e login.
- Apenas administradores podem acessar o gerenciamento de usuários.
- Tentativas de acesso administrativo sem permissão são bloqueadas pelo sistema.
- O projeto utiliza migrations para estruturar o banco de dados.

## Sobre o projeto

 Atividade prática de desenvolvimento web utilizando o framework Laravel.
 Aluna: Nayara Rita Macedo Amador RA:250357