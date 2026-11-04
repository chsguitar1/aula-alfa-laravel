<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Autenticação e área administrativa (atividade)

### Como rodar localmente
```bash
cp .env.example .env   # (ou crie o .env) e configure o banco
php artisan key:generate
php artisan migrate:fresh --seed   # cria o admin de demonstração (somente local/testing)
npm install && npm run build   # Node 26 (.nvmrc)
php artisan test --compact
```
Se o seu banco local já tinha uma tabela `profiles` (em vez de `profile`), use `migrate:fresh`.

Contas de demonstração (apenas ambiente local):

| Perfil | Email | Senha |
|---|---|---|
| Administrador | `admin@example.com` | `admin-local-123` (ou `SEED_ADMIN_PASSWORD` no `.env`) |
| Comum | crie em `/register` | a que você escolher (mín. 8 caracteres) |

### Rotas
| Rota | Acesso |
|---|---|
| `GET /` | autenticado (visitante → `/login`) |
| `GET /login`, `POST /login` | público; o POST tem `throttle:login` |
| `POST /logout` | autenticado |
| `GET /register`, `POST /store` | público (sempre cria usuário comum) |
| `/admin/*` (todas) | middleware `admin` (`EnsureAdmin`): visitante → `/login`, sem perfil admin → 403 |

### Limite de tentativas de login
Definido em `AppServiceProvider` (`RateLimiter::for('login', …)`): **5 requisições por minuto para cada combinação email + IP**. Ao estourar, o usuário volta ao formulário com a mensagem "Muitas tentativas… tente novamente em N segundos" (mesmo a senha correta é recusada até a janela expirar). O contador fica no cache da aplicação. Limitações: um atacante que troque de IP/rede ou teste muitos emails diferentes escapa do limite por chave; o ideal é somar limites por IP e por email, senhas fortes e monitoramento. Também pode bloquear temporariamente o dono legítimo da conta.

### Schema de `profile`
- A migration original cria a tabela `profile` (singular); o model agora declara `$table = 'profile'`.
- A coluna nasceu como `description` (NOT NULL) e foi renomeada para `address` por outra migration que apontava para `profiles` (quebrava em banco novo). Ela foi tornada tolerante (localiza a tabela real e só renomeia se necessário), e uma **nova** migration torna `address` nullable, como o formulário já assumia.
- O tipo administrativo é `profile.type = 'admin'`. O formulário de perfil só aceita `PF`/`PJ` e preserva o tipo `admin` quando existe.

### Perguntas para reflexão
- **Por que um autenticado ainda recebe 403?** Autenticação diz *quem você é*; autorização diz *o que você pode fazer*. Estar logado não implica ter perfil `admin`.
- **Risco de só esconder o botão?** Esconder é só UX: qualquer um pode digitar a URL ou forjar a requisição. A regra precisa ser validada no servidor (middleware).
- **Por que logout é POST + CSRF?** GET deve ser seguro/sem efeito colateral; um link ou `<img src="/logout">` em outro site deslogaria a vítima (CSRF de logout). POST com token garante que a ação partiu do nosso formulário.
- **Limite de tentativas: ajuda e limitações?** Ver seção acima.
- **Primeiro administrador sem expor ao público?** Seeder/comando artisan executado pelo responsável do ambiente (aqui, `AdminUserSeeder`, restrito a local/testing); em produção, um comando interativo ou `tinker` com senha própria, nunca um formulário ou campo `type` aceito do cliente.
