# Perguntas para reflexão

**1. Por que um usuário autenticado ainda pode receber 403?**
Autenticar é provar quem você é, e autorizar é ter permissão. O usuário comum faz login, mas o middleware vê que o perfil dele não é `admin` e responde 403.

**2. Qual o risco de confiar só em um botão escondido?**
Esconder o botão só muda a tela. Qualquer um pode digitar a URL ou mandar a requisição direto, então a proteção tem que estar no servidor (middleware).

**3. Por que o logout deve ser POST com CSRF?**
Com link GET, outro site poderia deslogar o usuário sem ele querer. Com POST e token CSRF, só o nosso formulário consegue fazer isso.

**4. Como o limite de tentativas ajuda e quais são as limitações?**
No projeto são 5 tentativas por minuto por email+IP, e a sexta retorna 429. Isso dificulta força bruta. Mas quem usa vários IPs contorna, e ele só atrasa o ataque, não impede. Também pode ser usado para bloquear o login de outra pessoa.

**5. Como criar o primeiro admin sem expor isso ao público?**
Pelo seeder, só em ambiente `local`, com senha de desenvolvimento. O cadastro público ignora o `type`, então sempre cria usuário comum.

# Prints dos cenários

1. `prints/01-visitante-abre-home-vai-para-login.png`
2. `prints/02-visitante-abre-admin-vai-para-login.png`
3. `prints/03-cadastro-conta-comum-confirmacao.png`
4. `prints/04-conta-comum-logada-menu-sair.png`
5. `prints/05-logout-volta-ao-login.png`
6. `prints/06-conta-comum-acesso-admin-403.png`
7. `prints/07-admin-acessa-painel.png`
