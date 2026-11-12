# Autenticação e área administrativa

Trabalho da disciplina de Framework com Laravel. A aplicação permite cadastro e login com sessão e restringe a área administrativa aos usuários com perfil `admin`.

## Integrantes

- James Soares Silva
- Giovanni Bernardi

## Requisitos

- PHP 8.4.1 ou superior, com as extensões exigidas pelo Composer, incluindo PDO SQLite.
- Composer e Git.
- Node.js 20.19+ ou 22.12+ e npm.

As dependências do `composer.lock` exigem PHP 8.4.1, embora o `composer.json` declare PHP 8.3. O projeto usa SQLite nas instruções abaixo; não é necessário iniciar o MySQL.

## Como executar no Windows

No PowerShell, ative o PHP portátil, se necessário, ajustando o caminho para a pasta onde está `php.exe`:

```powershell
$env:Path = "C:\caminho\para\php;$env:Path"
php -v
composer --version
```

Clone esta branch e entre na pasta, caso ainda não tenha feito isso:

```powershell
git clone --branch trabalho-auth --single-branch https://github.com/jamesoaress/aula-alfa-laravel.git
cd aula-alfa-laravel
```

Na pasta do projeto, execute:

```powershell
composer install
Copy-Item .env.example .env
php artisan key:generate
New-Item -ItemType File -Path database/database.sqlite
php artisan migrate --seed
npm.cmd install --package-lock=false
npm.cmd run build
php artisan serve --host=127.0.0.1 --port=8000
```

Abra <http://127.0.0.1:8000/login>. O comando `Copy-Item` e a criação do arquivo SQLite são necessários na primeira configuração; se já existirem, preserve o `.env` e o banco. `migrate --seed` cria as tabelas e os usuários de demonstração. Evite `migrate:fresh` em um banco com dados que deseja manter, pois ele apaga as tabelas.

O `package-lock.json` atual está inconsistente com o `package.json`: por isso as instruções usam `npm install --package-lock=false`. A instalação pode selecionar versões compatíveis mais recentes; atualizar e versionar o lockfile é necessário para instalações reproduzíveis com `npm ci`.

Para desenvolvimento visual, execute `npm.cmd run dev` em outro terminal. Execute `php artisan test --compact` para rodar os testes.

## Como funciona

- `/register` cria uma conta comum com nome, email único e senha de pelo menos oito caracteres. Após o cadastro, o visitante retorna ao login.
- `/login` recebe email e senha por POST com CSRF. Uma falha apresenta mensagem genérica e preserva apenas o email.
- Após o login, a sessão é regenerada. Usuários comuns vão para `/`; administradores vão para `/admin/users` ou para o destino interno solicitado anteriormente.
- As rotas em `/admin/*` passam por middleware: visitantes são redirecionados ao login, usuários comuns recebem HTTP 403 e usuários com `profile.type = admin` têm acesso.
- O botão **Sair** envia POST com CSRF. O logout invalida a sessão e renova o token CSRF.
- O login limita cinco tentativas malsucedidas por combinação de email e IP em uma janela de 60 segundos. O contador é limpo após o sucesso. O limite dificulta ataques de força bruta, mas pode ser contornado com vários IPs ou emails e não substitui outras medidas de segurança.
- O painel permite listar, editar e excluir usuários, atualizar perfis e registrar linguagens. O perfil de um administrador mantém sua permissão administrativa durante a edição.

## Administrador local e demonstração

Com `APP_ENV=local`, o seeder cria o seguinte usuário de exemplo:

- Email: `admin@example.com`
- Senha: `senha-local-123`

Esses dados são somente para demonstração local. Os valores podem ser alterados no `.env` antes de executar o seeder. O cadastro público não concede perfil administrativo.

Roteiro para apresentação e capturas de tela:

1. Sem login, abra `/` e `/admin/users`: ambas redirecionam para `/login`.
2. Abra `/register`, cadastre uma conta comum e confira a confirmação no login.
3. Entre com a conta comum, faça logout pelo botão **Sair** e entre novamente.
4. Com a conta comum, abra `/admin/users` e confira a resposta 403.
5. Faça logout, entre com o administrador local e acesse `/admin/users`.

## Estrutura principal

- `routes/web.php`: rotas públicas, autenticadas e administrativas.
- `app/Http/Controllers/AuthController.php`: login, limite de tentativas e logout.
- `app/Http/Middleware/EnsureAdmin.php`: autorização administrativa.
- `app/Http/Controllers/UserController.php`: cadastro e operações de usuários.
- `database/migrations` e `database/seeders`: schema e dados de demonstração.
- `tests/Feature/AuthAdminTest.php`: testes dos fluxos e permissões.

Mais detalhes e respostas às perguntas da atividade estão em [docs/atividade-autenticacao.md](docs/atividade-autenticacao.md).
