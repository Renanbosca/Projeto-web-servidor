PROJETO GERENCIAMENTO SISTEMA BIBLIOTECA
## Integrantes
* Renan Henrique dos Santos Bosca: Desenvolvimento front-end(views)/escrita README e revisão de códigos.
* HENRIQUE MENEZES TRESSOLDI: Desenvolvimento back-end

## Sobre o Projeto
Sistema web desenvolvido em PHP seguindo o padrão arquitetural MVC. O sistema permite o controlo de livros, leitores, utilizadores e empréstimos de uma biblioteca, armazenando os dados em ficheiros JSON.

## Funcionalidades
* **Autenticação:** Registo de utilizadores, login e proteção de rotas por sessão.
* **Painel:** Resumo com o total de livros, leitores, empréstimos ativos e alerta de atrasos.
* **Livros:** Gestão completa do acervo com verificação de disponibilidade.
* **Leitores:** Gestão de leitores com validação de limites de empréstimo.
* **Empréstimos:** Registo de empréstimos, cálculo de prazos e controle de devoluções.

## Requisitos
* PHP 8.0 ou superior.
* Servidor Web (Apache via XAMPP ou servidor embutido do PHP).

## Como Executar
1. Clone o repositório na pasta do seu servidor web (ex: `htdocs` do XAMPP).
2. Certifique-se de que a pasta `data/` tem permissão de escrita.
3. Aceda ao projeto através do navegador (ex: `http://localhost/biblioteca`).
## BUGS
* **VIEWS.PHP** Pode acontecer um bug com variaveis ao acessar a pastas views pelo vscode pode acontecer de mostrar que as variaveis não estao sendo iniciadas, porem é apenas um bug funcionam da mesma maneirda
