# Atividade prática: autenticação e área administrativa

## O que foi implementado

- `GET /login`, `POST /login` (`login.store`), `POST /logout` (`logout`): `AuthController`.
- Middleware `EnsureAdmin` aplicado ao grupo `admin/*` em `routes/web.php`.
- `GET /` protegido por `auth` (corrigido o middleware com erro de digitação `autenyticado`).
- Cadastro público (`/register` + `POST /store`) cria apenas usuários comuns.
- Botão **Sair** (formulário POST + CSRF) no menu do usuário em `parts/header.blade.php`.
- Rotas do painel preservadas com os mesmos nomes usados pelas views.
- `Profile` usa a tabela real `profile`. Nova migration troca `description` por `address` (nullable), sem alterar a migration original.
- Nova migration cria `user_languages` (usada pela tela de edição; faltava no schema inicial).
- `AdminUserSeeder`: admin de demonstração, só em ambiente `local`.
- Testes Pest em `tests/Feature/AuthAdminTest.php`.

## Limite de tentativas de login

Configurado em `AuthController`: **5 tentativas falhas por minuto**, por combinação de **email + IP**.

1. Antes de validar a senha, `RateLimiter::tooManyAttempts()` verifica se a chave já atingiu 5 falhas.
2. Cada falha chama `RateLimiter::hit()` (janela de 60 s).
3. Ao atingir o limite, o usuário vê "Muitas tentativas... tente novamente em N segundos", mesmo que acerte a senha.
4. Login com sucesso chama `RateLimiter::clear()`.

Limitações: o contador fica no cache (se o cache é limpo, zera); atacante que troca de IP ou testa muitos emails diferentes escapa da chave email+IP; pode ser usado para bloquear temporariamente a conta de outra pessoa (negação de serviço). Por isso complementa, mas não substitui, senhas fortes e monitoramento.

## Como demonstrar localmente

Para configurar o projeto no Windows com SQLite, siga o passo a passo do [README](../README.md). Ele inclui a criação do `.env` e do arquivo de banco, as migrations e o build dos assets. Com `APP_ENV=local`, o seeder cria o administrador de demonstração.

Admin de demonstração (somente local): `admin@example.com` / `senha-local-123`
(pode trocar com `ADMIN_SEED_EMAIL` e `ADMIN_SEED_PASSWORD` no `.env`).

Roteiro de capturas de tela:
1. Visitante abre `/` e `/admin/users` -> redireciona para `/login`.
2. Cadastro de conta comum em `/register` -> volta ao login com mensagem.
3. Login e logout (botão Sair) com essa conta.
4. Mesma conta abre `/admin/users` -> **403**.
5. Login com o admin -> `/admin/users`.

Testes: `php artisan test --compact`

## Perguntas de reflexão

**Por que um usuário autenticado ainda pode receber 403?**
Autenticação responde "quem é você?"; autorização responde "o que você pode fazer?". Estar logado não concede permissão: sem `profile.type = admin`, o servidor recusa com 403.

**Qual o risco de confiar apenas em um botão escondido?**
Esconder o botão é só aparência. Qualquer pessoa pode digitar a URL ou montar a requisição manualmente. A verificação precisa ocorrer no servidor (middleware), em toda rota.

**Por que logout deve ser POST com CSRF?**
Um GET pode ser disparado sem intenção (imagem, link, pré-carregamento de página) e deslogaria o usuário. POST com token CSRF garante que a ação partiu de um formulário do próprio site.

**Como o limite de tentativas ajuda e quais são suas limitações?**
Ver seção acima: dificulta força bruta, mas pode ser contornado com vários IPs/emails e pode bloquear usuários legítimos.

**Como provisionar o primeiro administrador sem expor isso ao público?**
Por seeder (ou comando artisan) executado só em ambiente controlado, com senha vinda do `.env`. Nenhum formulário público aceita `type`, e `Profile::type` não é definido por nenhuma rota pública.
