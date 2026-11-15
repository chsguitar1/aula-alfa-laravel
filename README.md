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

## Arquivos de Yharnam — autenticação (Dev 1)

O login usa `GET /login` e `POST /login`, com formulário independente, validação,
CSRF e mensagem genérica para credenciais inválidas. Somente o email é preservado
após uma falha. O identificador da sessão é renovado ao entrar.
Usuários comuns seguem para `/`; administradores (`profile.type = admin`) seguem
para `/admin/users`, respeitando o destino interno salvo anteriormente.
A página inicial exige autenticação. O formulário de saída usa `POST /logout`
com CSRF, invalida a sessão, renova o token e retorna ao login; não há logout GET.

### Limite de tentativas

São permitidas cinco falhas de autenticação por combinação de email (convertido
para minúsculas na chave do limitador) e IP. A janela de 60 segundos começa na
primeira falha: novas falhas não prolongam essa janela. Após a quinta falha,
as próximas tentativas são recusadas, mesmo com a senha correta, até expirar o
prazo. A mensagem informa os segundos restantes. Um login bem-sucedido antes do
bloqueio limpa o contador. Entradas que falham na validação do formulário não
contam como falhas de autenticação. O estado usa o cache configurado no Laravel;
um cache persistente e compartilhado é necessário entre processos/servidores.
Este limite não impede tentativas distribuídas entre vários IPs ou emails.

### Verificação sem alterar os dados locais

Execute `php artisan test --compact` e `npm run build`.
Os testes de autenticação usam SQLite `:memory:` e cache/sessão `array`; verificam
essa configuração antes de acessar o banco. As contas são criadas por factories
somente no banco de teste. Não é necessário executar seeders ou migrations no
banco local para rodar os testes.

A cobertura inclui login comum e administrativo (redirecionamento), validação,
mensagem genérica, preservação do email, rotação e continuidade da sessão,
logout, CSRF, proteção da página inicial, expiração e isolamento do limite de
tentativas por email/IP e carregamento dos scripts no layout compartilhado.

### Cadastro e administração (Dev 2)

O cadastro independente em `GET /register` envia nome, email e senha (mínimo
8 caracteres) para `POST /store`, com CSRF, email único e erros por campo.
Somente esses três campos são aceitos: o cadastro público nunca cria perfil
administrativo. O visitante retorna ao login com confirmação; um administrador
que cadastrar pelo painel retorna à listagem.

Todas as rotas `/admin/*` usam `EnsureAdmin`: visitantes seguem para o login,
contas comuns recebem 403 e contas com `profile.type = admin` acessam o painel.
A edição PF/PJ preserva o tipo de administradores existentes. As rotas utilizadas
pelas views foram integradas com o login do Dev 1.

O model `Profile` usa a tabela existente `profile`. Uma nova migration adiciona
`address` sem remover `description` nem modificar migrations compartilhadas.
Outra cria `user_languages`, com exclusão em cascata e linguagens únicas por
usuário. Em um banco local já configurado, execute `php artisan migrate` para
aplicar somente as migrations pendentes.

#### Administrador local de demonstração

Configure localmente `APP_ENV=local`, `DEMO_ADMIN_EMAIL` e
`DEMO_ADMIN_PASSWORD` (mínimo 12 caracteres), sem versionar os valores.
`DEMO_ADMIN_NAME` é opcional. Em seguida execute:

```bash
php artisan db:seed --class=DemoAdminSeeder
```

O seeder recusa outros ambientes e credenciais ausentes. Executá-lo novamente
não redefine a senha; ele também recusa promover uma conta comum existente.

Validação: `php artisan test --compact` e `npm run build`. Os testes do Dev 2
usam banco SQLite em memória e cobrem cadastro, validação, CSRF, privilégios,
CRUD administrativo, perfis, linguagens, migração compatível, seeder e o ciclo
cadastro → login → acesso negado/painel → logout integrado ao Dev 1.