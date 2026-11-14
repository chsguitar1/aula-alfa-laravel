# Aula Alfa Laravel

Aplicação Laravel para autenticação, cadastro de usuários e área administrativa.

## Requisitos

- PHP 8.3 ou superior
- Composer
- Node.js e npm
- Banco de dados SQLite (padrão do projeto) ou outro driver suportado pelo Laravel

## Clonar e instalar

```bash
git clone https://github.com/chsguitar1/aula-alfa-laravel.git
cd aula-alfa-laravel
composer install
npm install
```

## Configurar ambiente

Crie o arquivo `.env` e gere a chave da aplicação:

```bash
cp .env.example .env
php artisan key:generate
```

Se o projeto não tiver um `.env.example`, crie o `.env` manualmente com as variáveis mínimas do Laravel e rode:

```bash
php artisan key:generate
```

Para o banco SQLite padrão, verifique se o arquivo existe:

```bash
touch database/database.sqlite
```

No `.env`, a configuração padrão pode ser:

```env
APP_NAME="Aula Alfa Laravel"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=/caminho/para/o/projeto/database/database.sqlite
```

## Rodar o projeto

### Opção rápida

```bash
composer run setup
composer run dev
```

A rotina `composer run setup` instala dependências, cria o `.env` quando possível, gera a chave, executa as migrations e compila os assets do frontend.

### Rodando em desenvolvimento

```bash
php artisan migrate
npm run dev
php artisan serve
```

A aplicação fica disponível em:

```text
http://127.0.0.1:8000
```

### Compilar assets para produção

```bash
npm run build
```

## Testes

```bash
php artisan test
```

## Estrutura principal

- `app/` — modelos, controllers e lógica da aplicação
- `database/migrations/` — migrations do banco
- `resources/views/` — views Blade
- `routes/web.php` — rotas da aplicação
- `public/` — assets públicos e ponto de entrada

## Observações

- O projeto usa Laravel 13 e Vite para o frontend.
- O comando `composer run dev` é a forma mais prática para iniciar o ambiente em desenvolvimento.
- Caso use outra base de dados, ajuste as variáveis `DB_*` no `.env` antes de rodar as migrations.
