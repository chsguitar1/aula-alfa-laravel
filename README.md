# Trabalho Laravel

Sistema de cadastro de usuários com login, logout e controle de acesso por perfil (usuário comum e administrador), feito em Laravel com AdminLTE.

## Requisitos

- PHP 8.3 ou superior
- Composer
- Node.js e npm
- Docker (para o MySQL)

## Como rodar

```bash
# 1. Instalar dependências
composer install
npm install

# 2. Criar o arquivo de ambiente e gerar a chave
cp .env.example .env
php artisan key:generate

# 3. Subir o banco MySQL
docker compose up -d

# 4. Criar as tabelas e os dados iniciais (inclui o administrador)
php artisan migrate:fresh --seed

# 5. Compilar o front-end
npm run build

# 6. Iniciar o servidor
php artisan serve
```

Acesse `http://localhost:8000`.

Se o banco ainda estiver iniciando, aguarde alguns segundos antes do passo 4.

## Usuário administrador

O administrador é criado pelo seeder `AdminUserSeeder`, somente com `APP_ENV=local`:

| Email | Senha |
|---|---|
| `admin@example.com` | `admin12345` |

A senha é apenas de desenvolvimento. O cadastro público (`/register`) sempre cria usuário comum.

## Telas

| Rota | Quem acessa | Descrição |
|---|---|---|
| `/login` | Todos | Login com email e senha |
| `/register` | Todos | Cadastro de usuário comum |
| `/` | Usuário logado | Página inicial |
| `/admin/users` | Somente administrador | Listagem de usuários |
| `/admin/users/{id}/edit` | Somente administrador | Edição de usuário |

Regras de acesso:

- Visitante em `/` ou `/admin/users` é redirecionado ao login.
- Usuário comum em `/admin/*` recebe 403.
- O botão Sair (menu do usuário, canto superior direito) encerra a sessão e volta ao login.

## Testes

```bash
php artisan test
```
