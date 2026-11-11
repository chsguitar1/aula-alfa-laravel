# Guia de Aplicação, Demonstração e Respostas da Atividade Prática

Este pacote contém todos os arquivos prontos na estrutura exata de pastas do projeto `aula-alfa-laravel`.

---

## 1. Como aplicar no projeto

1. Extraia o conteúdo deste arquivo `.zip` diretamente na raiz do seu repositório `aula-alfa-laravel` (substituindo os arquivos existentes quando solicitado).
2. No terminal, na pasta raiz do projeto, execute:
   ```bash
   php artisan migrate
   php artisan db:seed
   npm run build
   php artisan serve
   ```
3. Contas provisionadas pelo `DatabaseSeeder`:
   - **Administrador:** `admin@alfa.com` | Senha: `senha123` (`profile.type = 'admin'`)
   - **Usuário Comum:** `usuario@alfa.com` | Senha: `senha123` (`profile.type = 'user'`)

---

## 2. Explicação do Limite de Tentativas de Login (Rate Limiting)

O limite de tentativas de login foi configurado em duas camadas complementares:
1. **Middleware de Rota (`throttle:5,1` em `routes/web.php`):** Limita requisições `POST /login` a no máximo **5 tentativas por minuto** por endereço IP/sessão, retornando status HTTP `429 Too Many Requests` caso excedido.
2. **Controle Granular por Credencial (`RateLimiter` em `AuthController.php`):** Utiliza uma chave composta por `email + IP` (`Str::lower($request->input('email')) . '|' . $request->ip()`). A cada falha de senha (`RateLimiter::hit($throttleKey, 60)`), o contador incrementa com tempo de expiração de 60 segundos. Após 5 falhas consecutivas, `RateLimiter::tooManyAttempts($throttleKey, 5)` bloqueia novas verificações de senha e exibe ao usuário quantos segundos faltam para tentar novamente. Quando o login é bem-sucedido, `RateLimiter::clear($throttleKey)` zera o contador.

---

## 3. Roteiro dos 5 Cenários para Captura de Tela (Entrega)

1. **Visitante tentando abrir `/` e `/admin/users`:**
   - Em janela anônima (deslogado), acesse `http://127.0.0.1:8000/` e `http://127.0.0.1:8000/admin/users`.
   - Em ambos os casos o visitante é redirecionado automaticamente para `http://127.0.0.1:8000/login`.
2. **Cadastro de uma conta comum:**
   - Acesse `http://127.0.0.1:8000/register` (ou clique em "Cadastre-se" na tela de login).
   - Preencha Nome, Email e Senha e clique em **Cadastrar**.
   - Você será redirecionado para `/login` com a mensagem verde de confirmação e o usuário terá `type = 'user'`.
3. **Login e logout dessa conta:**
   - Faça login com a conta recém-criada. Você será encaminhado para `/`.
   - Clique no botão **Sair** no cabeçalho (`POST /logout` com CSRF) e veja o retorno para `/login`.
4. **Tentativa de acesso administrativo por conta comum (403 Forbidden):**
   - Logado com a conta comum (`usuario@alfa.com` / `senha123`), acesse `http://127.0.0.1:8000/admin/users`.
   - O `AdminMiddleware` bloqueará o acesso exibindo a tela **403 | Acesso negado. Esta área é restrita a administradores.**
5. **Login de administrador e acesso ao painel:**
   - Faça login com `admin@alfa.com` e senha `senha123`.
   - Você será redirecionado diretamente para `/admin/users`, visualizando a listagem de usuários do painel administrativo.

---

## 4. Respostas às Perguntas para Reflexão

1. **Por que um usuário autenticado ainda pode receber 403?**
   Porque **autenticação** (confirmar *quem* é o usuário por meio do login/sessão) é diferente de **autorização** (verificar *o que* esse usuário tem permissão para acessar). Um usuário comum autenticado tem sessão válida (não recebe 401/redirect para login), mas não possui privilégio `type = 'admin'`, portanto o servidor nega o acesso ao recurso com o status HTTP `403 Forbidden`.

2. **Qual seria o risco de confiar apenas em um botão escondido na interface?**
   Ocultar botões no HTML/Blade é apenas uma conveniência visual (segurança por obscuridade) e não impede que qualquer usuário digite a URL diretamente no navegador (`GET /admin/users`) ou envie requisições forjadas via `curl`/Insomnia/Postman. Sem validação no back-end via Middleware, todas as rotas administrativas ficariam vulneráveis a acesso direto (Broken Access Control).

3. **Por que logout deve ser uma requisição POST protegida por CSRF?**
   Se o logout fosse feito via `GET /logout`, qualquer página externa ou imagem maliciosa (`<img src="https://seusite.com/logout">`) poderia encerrar a sessão do usuário sem o consentimento dele (ataque de *Cross-Site Request Forgery* / DoS de sessão), além de navegadores com pré-carregamento de links encerrarem a sessão acidentalmente. Usar `POST` com token `@csrf` garante que o encerramento partiu intencionalmente da própria aplicação.

4. **Como o limite de tentativas de login ajuda e quais são suas limitações?**
   O *Rate Limiting* mitiga ataques de força bruta (*brute force*) e *credential stuffing*, tornando inviável testar milhares de senhas por minuto contra uma conta. Suas limitações são: (a) atacantes com redes distribuídas (botnets/proxies) podem alternar IPs se o limite for apenas por IP; e (b) se o bloqueio for apenas pelo e-mail, um atacante pode causar negação de serviço temporária (*account lockout*) contra um usuário legítimo tentando repetidamente errar a senha dele.

5. **Como provisionar o primeiro administrador sem expor essa capacidade ao público?**
   O primeiro administrador deve ser criado por mecanismos internos controlados no servidor — como *Database Seeders* (`php artisan db:seed`), comandos Artisan customizados via CLI, migrations iniciais de dados ou promoção direta no banco de dados por quem detém acesso à infraestrutura — nunca expondo um campo `type = 'admin'` em formulários públicos de cadastro (evitando vulnerabilidades de *Mass Assignment* ou escalação de privilégio).
