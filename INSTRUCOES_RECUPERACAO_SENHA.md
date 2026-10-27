# Recuperação de senha — Laravel

## O que foi implementado

- Formulário de solicitação do link (`GET /forgot-password`)
- Envio do link (`POST /forgot-password`)
- Formulário para nova senha (`GET /reset-password/{token}`)
- Atualização da senha com token válido (`POST /reset-password`)
- Métodos no `UserController` e views com layout AdminLTE do projeto

## Como executar

1. Execute `composer install` e `npm install`.
2. Copie `.env.example` para `.env` e execute `php artisan key:generate`.
3. Configure o banco no `.env` e execute `php artisan migrate`.
4. Execute `npm run dev` e, em outro terminal, `php artisan serve`.
5. Acesse `http://127.0.0.1:8000/forgot-password` e informe o e-mail de um usuário cadastrado.
6. Com `MAIL_MAILER=log` (padrão do `.env.example`), procure a URL enviada no arquivo `storage/logs/laravel.log` e abra-a no navegador.
7. Informe a nova senha duas vezes e envie para concluir.

**Observação:** o projeto original não contém tela de login, portanto o link de retorno foi direcionado ao cadastro. Para envio real de e-mails, configure um serviço de SMTP no `.env`.
