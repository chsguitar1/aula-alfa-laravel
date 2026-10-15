# Trabalho Laravel - Recuperação de senha

Implementação baseada no repositório de aulas de chsguitar1/aula-alfa-laravel e no formulário forgot-password do AdminLTE indicado no enunciado.

## Requisitos atendidos

- View de recuperação: resources/views/user/forgot-password.blade.php, usando o layout AdminLTE do projeto.
- UserController::forgotPassword retorna a view; sendResetLink recebe o POST e envia o link.
- UserController::resetPassword retorna o formulário de nova senha; updatePassword altera a senha.
- Rotas GET e POST em routes/web.php.
- Formulários com CSRF, validação, mensagens em português e confirmação da senha.
- Senha com hash, token de uso único com validade de 60 minutos e limite de requisições.
- A tabela password_reset_tokens já existe na migration original; não é necessário criar uma tabela adicional.

## Executar

Requer PHP 8.3 ou superior e Composer. Os estilos e scripts compilados já acompanham a entrega, então Node.js não é necessário para apenas executar.

No terminal, dentro da pasta do projeto:

```sh
composer install
```

Copie .env.example para .env. No Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Depois execute:

```sh
php artisan key:generate
php artisan migrate
php artisan serve
```

O banco padrão é SQLite, que dispensa servidor de banco. Caso o comando migrate peça autorização para criar o arquivo SQLite, confirme. Para usar MySQL, crie um banco vazio e configure DB_CONNECTION=mysql, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME e DB_PASSWORD no .env antes das migrations.

Abra http://localhost:8000/forgot-password.

## Conferir o fluxo completo

1. Acesse http://localhost:8000/register e cadastre um usuário com senha de pelo menos 8 caracteres.
2. Acesse /forgot-password e informe o e-mail cadastrado.
3. A configuração MAIL_MAILER=log registra a mensagem em storage/logs/laravel.log. Abra esse arquivo e copie a URL completa que contém /reset-password/ e o e-mail. O link não é enviado para uma caixa de e-mail real nessa configuração.
4. Abra o link no navegador; informe e confirme a nova senha.
5. O sistema exibirá a confirmação de alteração. O link não poderá ser usado novamente.

Para envio real por e-mail, configure MAIL_MAILER=smtp e as credenciais MAIL_* no .env. A configuração log permite demonstrar o exercício sem contratar ou configurar um serviço externo.

## Validação realizada

11 testes passaram, com 30 verificações. A compilação dos assets e as migrations SQLite foram executadas com sucesso. Também foi corrigida a referência antiga a resources/css/app.css na tela inicial, para usar o SCSS configurado no projeto.

## Testes

```sh
php artisan test
```

A suíte verifica as telas, a notificação, a troca com hash, a confirmação, a senha curta, os tokens inválidos, expirados e reutilizados, e o comportamento para e-mail inexistente.

## Arquivos principais

- app/Http/Controllers/UserController.php
- routes/web.php
- resources/views/user/forgot-password.blade.php
- resources/views/user/reset-password.blade.php
- resources/views/user/create.blade.php (link de acesso à recuperação)
- resources/views/layouts/default.blade.php (idioma pt-BR)
- lang/pt_BR/passwords.php e validation.php
- tests/Feature/PasswordRecoveryTest.php

A estrutura e as funções de cadastro/listagem do projeto de aulas foram preservadas. Não foi incluída uma tela de login, pois o enunciado e o projeto-base não a exigem. Para recompilar estilos após alterações: npm install e npm run build.
