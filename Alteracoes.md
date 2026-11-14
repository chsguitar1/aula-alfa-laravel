# Alterações e funcionamento

Este arquivo registra o fluxo implementado para autenticação, cadastro e administração de usuários.

## Autenticação e cadastro

- `GET /login` exibe um formulário Blade independente do layout administrativo.
- `POST /login` valida e-mail e senha, autentica pela sessão e regenera o identificador da sessão.
- Credenciais inválidas mostram uma mensagem genérica; o e-mail é preservado e a senha não é preenchida novamente.
- O login aceita até **5 requisições por minuto** para cada combinação de e-mail normalizado e endereço IP. A sexta requisição dentro do intervalo recebe HTTP 429. O limite conta as requisições ao endpoint, inclusive um login bem-sucedido.
- Usuários comuns são enviados para `/`. Administradores são enviados para `/admin/users`, exceto quando há um destino interno anterior válido. Destinos externos não são aceitos.
- `POST /logout` só aceita usuários autenticados, invalida a sessão, regenera o token CSRF e redireciona para `/login`. O formulário Sair do cabeçalho usa POST e CSRF.
- `GET /register` e `POST /store` permitem cadastro público com nome, e-mail único e senha de pelo menos 8 caracteres. O cadastro não cria perfil administrativo nem aceita privilégios enviados pelo formulário.
- Após cadastro público, a pessoa é encaminhada ao login com uma confirmação. Se um administrador usar o cadastro para incluir uma conta, retorna à lista administrativa.

## Autorização administrativa

O middleware `EnsureAdmin` protege o grupo de rotas `/admin/*`:

- Visitantes são redirecionados ao login; o endereço solicitado fica disponível para o retorno após autenticação.
- Usuários autenticados sem um perfil cujo `type` seja `admin` recebem HTTP 403.
- Somente perfis com `type = admin` podem acessar as funcionalidades administrativas.

O grupo contém a listagem, visualização, edição, atualização, exclusão e atualização de perfil e linguagens dos usuários. A autorização é aplicada no servidor; ocultar links na interface não substitui essa verificação.

## Perfil, linguagens e administrador local

- O modelo `Profile` usa a tabela singular `profile`, conforme a migration existente.
- Como a migration já aplicada contém `description`, ela foi mantida intacta. Uma migration adicional acrescenta `address`.
- A edição administrativa de linguagens usa a relação `User::languages` e a tabela `user_languages`; a atualização substitui as linguagens selecionadas.
- O administrador de demonstração é provisionado por `LocalAdminSeeder`, que só executa com `APP_ENV=local`. Configure `LOCAL_ADMIN_EMAIL` e `LOCAL_ADMIN_PASSWORD` no `.env`; nenhuma senha de demonstração é armazenada no código.

## Como preparar e executar

### Primeira configuração

Requisitos: PHP 8.3+, Composer, Node.js/npm e SQLite (ou outro banco configurado para Laravel).

No estado atual do repositório não há `.env.example`. Em uma cópia nova, instale as dependências e crie `.env` manualmente com, no mínimo, `APP_NAME`, `APP_ENV=local`, `APP_KEY` vazio, `APP_DEBUG=true`, `APP_URL`, `DB_CONNECTION=sqlite` e `DB_DATABASE` apontando para o caminho absoluto de `database/database.sqlite`.

```bash
composer install
npm install
touch database/database.sqlite
php artisan key:generate
php artisan migrate
```

Se um arquivo `.env.example` for adicionado futuramente, ele poderá ser copiado para `.env` antes de gerar a chave. Não compartilhe o `.env` nem as credenciais do administrador.

### Desenvolvimento com Herd

Para este projeto, a forma recomendada é deixar o Laravel Herd servir a aplicação e usar Vite para os assets:

```bash
php artisan migrate
npm run dev
```

Mantenha `npm run dev` ativo enquanto desenvolve e abra o domínio local configurado no Herd. Não execute também `php artisan serve` nesse fluxo, para evitar iniciar um segundo servidor PHP.

Para criar o administrador local de demonstração, primeiro configure no `.env`:

```env
APP_ENV=local
LOCAL_ADMIN_EMAIL=seu-email-local
LOCAL_ADMIN_PASSWORD=sua-senha-local-forte
```

Depois execute:

```bash
php artisan db:seed --class=LocalAdminSeeder
```

Se a configuração estiver em cache, execute `php artisan config:clear` após alterar o `.env`.

### Testes e build

```bash
php artisan test --compact
npm run build
```

Os testes usam SQLite em memória; não é necessário executar migrations no banco de desenvolvimento para rodá-los.