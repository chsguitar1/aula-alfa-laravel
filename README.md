# Aula Alfa Laravel — Autenticação e Controle de Acesso

Projeto acadêmico desenvolvido em Laravel para implementar cadastro de usuários, autenticação, logout e controle de acesso à área administrativa.

O projeto foi desenvolvido a partir do repositório disponibilizado na disciplina, utilizando uma branch separada para a implementação.

## 1. Tecnologias utilizadas

- PHP 8.4
- Laravel 13
- Composer
- Node.js e npm
- SQLite
- Blade
- Bootstrap e AdminLTE
- Pest PHP para testes automatizados
- Git e GitHub

## 2. Requisitos para executar o projeto

Antes de iniciar, é necessário ter instalado:

- PHP 8.4.1 ou superior, compatível com as dependências utilizadas no projeto.
- Composer.
- Node.js e npm.
- Git.
- Extensões PHP necessárias, principalmente `openssl`, `mbstring`, `fileinfo`, `pdo_sqlite` e `sqlite3`.

No computador da faculdade, o XAMPP possui PHP 8.0, que não atende aos requisitos das dependências instaladas. Por isso, utilizamos PHP 8.4 separadamente.

## 3. Configurar o PHP 8.4 no Git Bash

### 3.1. Verificar a versão ativa

Abra o Git Bash e execute:

```bash
php -v
```

Se aparecer PHP 8.0, significa que o terminal está utilizando a versão antiga.

### 3.2. Ativar o PHP 8.4 no computador da faculdade

No computador utilizado durante o desenvolvimento, o PHP 8.4 foi instalado pelo WinGet.

Para utilizá-lo, execute:

```bash
export PATH="/c/Users/UniALFA/AppData/Local/Microsoft/WinGet/Packages/PHP.PHP.8.4_Microsoft.Winget.Source_8wekyb3d8bbwe:$PATH"
```

Depois, confira:

```bash
php -v
which php
```

A versão deve aparecer como PHP 8.4.x.

**Importante:** o comando `export PATH` altera a configuração apenas do terminal atual. Ao abrir outro Git Bash, será necessário executá-lo novamente.

Em outros computadores, o caminho poderá ser diferente. Nesse caso, instale uma versão compatível do PHP e configure o PATH para apontar para ela.

### 3.3. Verificar as extensões PHP

Execute:

```bash
php --ini
php -m
```

O primeiro comando mostra qual arquivo `php.ini` está sendo utilizado.

O segundo mostra as extensões habilitadas.

Caso alguma extensão esteja desabilitada, configure o `php.ini` correspondente ao PHP 8.4 e abra novamente o terminal.

Não é recomendado alterar arquivos internos do Composer para ignorar erros de versão do PHP.

## 4. Clonar o projeto

Para baixar o repositório utilizado no desenvolvimento:

```bash
git clone https://github.com/gui-ritelli/aula-alfa-laravel.git
cd aula-alfa-laravel
```

A implementação foi desenvolvida na branch:

```text
feature/autenticacao-admin
```

Depois que a branch estiver publicada, será possível acessá-la com:

```bash
git fetch origin
git switch feature/autenticacao-admin
```

Caso a branch ainda não tenha sido enviada, ela estará disponível somente no computador em que foi criada.

## 5. Instalar as dependências

Dentro da pasta do projeto, execute:

```bash
composer install
npm install
```

O Composer instala as dependências PHP.

O npm instala as dependências utilizadas nos arquivos visuais do projeto.

Caso apareça um erro informando que a versão do PHP é incompatível, verifique novamente a seção 3.

## 6. Configurar o ambiente

Crie o arquivo `.env` a partir do modelo:

```bash
cp .env.example .env
```

No arquivo `.env`, confira as seguintes configurações:

```dotenv
APP_NAME="Aula Alfa Laravel"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

O arquivo `.env` é utilizado para armazenar as configurações locais da aplicação.

Ele não deve ser enviado ao GitHub, principalmente porque pode conter informações privadas.

Já o `.env.example` é o modelo utilizado por quem for configurar o projeto em outro computador.

## 7. Configurar o banco SQLite

O projeto utiliza SQLite, sem necessidade de instalar MySQL ou configurar um servidor de banco de dados separado.

No Git Bash, crie o arquivo do banco caso ele ainda não exista:

```bash
mkdir -p database
touch database/database.sqlite
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

Execute as migrations:

```bash
php artisan migrate
```

Esses comandos preparam o banco de dados e criam as tabelas necessárias.

Se houver problemas com configurações antigas, execute:

```bash
php artisan config:clear
```

## 8. Criar um administrador para testes

O sistema possui um Seeder para criar um usuário administrador em ambiente local.

No arquivo `.env`, configure:

```dotenv
ADMIN_SEED_EMAIL=admin@exemplo.com
ADMIN_SEED_PASSWORD=SUA_SENHA_DE_TESTE
```

Substitua `SUA_SENHA_DE_TESTE` por uma senha escolhida por você.

Depois execute:

```bash
php artisan config:clear
php artisan db:seed --class=AdminUserSeeder
```

O Seeder foi preparado para:

- Funcionar somente no ambiente local.
- Utilizar as credenciais configuradas no `.env`.
- Criar um usuário administrador quando o email ainda não existe.
- Impedir que uma conta comum existente seja transformada em administradora automaticamente.
- Preservar a senha de um administrador que já tenha sido criado.

As credenciais reais não devem ser registradas no README nem enviadas ao GitHub.

## 9. Executar o projeto

Primeiro, compile os arquivos do frontend:

```bash
npm run build
```

Depois inicie o servidor Laravel:

```bash
php artisan serve
```

O endereço padrão utilizado durante os testes foi:

http://127.0.0.1:8000

Principais páginas:

| Página | Endereço |
|---|---|
| Login | `/login` |
| Cadastro | `/register` |
| Página inicial | `/` |
| Painel administrativo | `/admin/users` |

## 10. Funcionalidades implementadas

### Cadastro de usuários

O cadastro público permite informar nome, email e senha.

O sistema valida os dados, impede emails duplicados e exige uma senha de pelo menos oito caracteres.

Após o cadastro, o usuário recebe automaticamente o perfil `PF`, correspondente a um usuário comum.

O formulário público não permite criar contas administrativas.

### Login

A autenticação utiliza os recursos do Laravel.

O sistema verifica as credenciais e regenera a sessão após o login.

Quando o email ou a senha estão incorretos, é exibida uma mensagem genérica de erro.

A rota possui limitação de cinco tentativas por minuto, reduzindo tentativas repetidas de autenticação.

### Redirecionamento após login

O comportamento depende do perfil:

- Usuário comum: redirecionado para a página inicial.
- Administrador: redirecionado para o painel administrativo.

O sistema também verifica os destinos de redirecionamento para impedir encaminhamento a sites externos.

### Logout

O logout é realizado através de uma requisição POST.

O sistema encerra a autenticação, invalida a sessão e regenera o token CSRF.

Após sair, o usuário é redirecionado para a página de login.

### Controle de acesso administrativo

As rotas administrativas utilizam os middlewares `auth` e `EnsureAdmin`.

Um visitante sem autenticação é redirecionado para o login.

Um usuário autenticado com perfil comum recebe HTTP 403 ao tentar acessar o painel administrativo.

Somente usuários com perfil `admin` podem acessar as funcionalidades administrativas.

### Gerenciamento de usuários

O painel administrativo permite:

- Listar usuários cadastrados.
- Visualizar informações de um usuário.
- Editar nome e email.
- Atualizar o perfil de usuários comuns para `PF` ou `PJ`.
- Excluir usuários.

O formulário comum de edição não permite modificar o tipo de um perfil administrativo.

## 11. Rotas administrativas

As seguintes rotas estão protegidas por autenticação e autorização administrativa:

| Método | Rota | Finalidade |
|---|---|---|
| GET | `/admin/users` | Listar usuários |
| GET | `/admin/users/{id}` | Visualizar usuário |
| GET | `/admin/users/{id}/edit` | Abrir edição |
| PUT | `/admin/users/{id}` | Atualizar dados |
| DELETE | `/admin/users/{id}` | Excluir usuário |
| PUT | `/admin/users/{user}/profile` | Atualizar perfil |

Para verificar os middlewares dessas rotas:

```bash
php artisan route:list -v --path=admin
```

## 12. Testes automatizados

O projeto utiliza Pest PHP para verificar o comportamento do sistema.

Os testes são configurados no arquivo `phpunit.xml` e utilizam SQLite em memória.

Isso permite executar testes que criam usuários sem modificar o banco local utilizado durante o desenvolvimento.

Para executar todos os testes:

```bash
php artisan test
```

Para executar somente os testes de autenticação:

```bash
php artisan test --filter=AuthenticationTest
```

Para executar os testes adicionais de segurança:

```bash
php artisan test --filter=LoginSecurityTest
```

Na última validação realizada durante o desenvolvimento, foram aprovados:

**17 testes e 53 assertions.**

Os cenários incluem:

- Redirecionamento de visitantes para o login.
- Acesso público às páginas de login e cadastro.
- Cadastro de usuário comum.
- Impedimento de email duplicado.
- Login com credenciais válidas e inválidas.
- Redirecionamento de usuário comum.
- Redirecionamento de administrador.
- Acesso administrativo autorizado.
- Bloqueio HTTP 403 para usuário comum.
- Encerramento da sessão após logout.
- Bloqueio de redirecionamento externo após login.
- Limitação de tentativas de autenticação.

Os testes devem ser executados novamente após alterações no código.

## 13. Problemas comuns e soluções

### PHP antigo no terminal

Problema:

```text
Composer dependencies require PHP >= 8.4.1
You are running PHP 8.0
```

Solução: executar novamente o comando `export PATH` da seção 3 e verificar `php -v`.

### Extensão SQLite ausente

Problema:

```text
could not find driver
```

Solução: verificar se `pdo_sqlite` e `sqlite3` estão habilitadas no `php.ini` do PHP 8.4.

### Extensão mbstring ausente

Solução: habilitar `mbstring` no arquivo `php.ini` e reabrir o terminal.

### Chave da aplicação ausente

Execute:

```bash
php artisan key:generate
```

### Tabela não encontrada

Confira as configurações do banco e execute:

```bash
php artisan migrate
```

### Git informa que não está em um repositório

É necessário executar os comandos dentro da pasta correta.

No computador utilizado no desenvolvimento:

```bash
cd ~/Desktop/aula-alfa-laravel-main/aula-alfa-laravel
```

### Erro de namespace em arquivo PHP

Verifique se o arquivo começa diretamente com `<?php`, sem linhas vazias antes dessa declaração.

### Usuário comum recebe erro 403

Esse comportamento é esperado quando um usuário sem perfil administrativo tenta acessar `/admin/users`.

## 14. Segurança e boas práticas

Durante a implementação, foram utilizados:

- Validação de dados recebidos dos formulários.
- Proteção CSRF.
- Autenticação baseada em sessão.
- Regeneração de sessão após login.
- Invalidação da sessão após logout.
- Middleware para restringir a área administrativa.
- Controle de perfil no banco de dados.
- Limitação de tentativas de login.
- Proteção contra redirecionamentos externos.
- Separação de credenciais locais no `.env`.
- Seeder administrativo restrito ao ambiente local.
- Testes automatizados de autenticação e autorização.

O projeto foi configurado para fins acadêmicos e execução local. Configurações de desenvolvimento, como `APP_DEBUG=true`, não devem ser utilizadas em produção.

## 15. Conferências antes da entrega

Para verificar os testes:

```bash
php artisan test
```

Para verificar as rotas administrativas:

```bash
php artisan route:list -v --path=admin
```

Para verificar a compilação:

```bash
npm run build
```

Para verificar as alterações do Git:

```bash
git diff --check
git status --short
```

Para confirmar que o `.env` não será enviado ao repositório:

```bash
git check-ignore .env
git ls-files .env
```

O primeiro comando deve identificar o `.env` como ignorado, enquanto o segundo não deve listar o arquivo.

## 16. Entrega no GitHub

O desenvolvimento foi realizado na branch:

```text
feature/autenticacao-admin
```

Após validar o funcionamento do sistema, os arquivos serão enviados ao fork no GitHub.

A entrega será realizada através de um Pull Request para a branch indicada pelo professor.

As evidências de funcionamento, quando solicitadas, devem demonstrar o cadastro, login, bloqueio de acesso, painel administrativo e logout.

---

**Projeto acadêmico — Laravel, autenticação e controle de acesso.**