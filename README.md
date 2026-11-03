
# PROJETO GERENCIAMENTO SISTEMA BIBLIOTECA

## Integrantes
* **Renan Henrique dos Santos Bosca:** 
  * PARTE 1: Desenvolvimento front-end (views), escrita do README e revisão de códigos. 
  * PARTE 2: Reestruturação para Orientação a Objetos (OO), integração com PDO e criação da base de dados relacional (MySQL).
* **Henrique Menezes Tressoldi:** 
  * PARTE 1: Desenvolvimento back-end (Lógica, Controladores e Regras de Negócio).
  * PARTE 2: Integração do composer ao projeto e implementação do sistema de Rotas.

## Sobre o Projeto
Sistema web desenvolvido em PHP seguindo o padrão arquitetural MVC (Model-View-Controller). O sistema permite o controlo de livros, leitores, utilizadores e empréstimos de uma biblioteca. Na sua segunda fase, o projeto foi totalmente refatorado para o paradigma de Orientação a Objetos e passou a utilizar a interface PDO (PHP Data Objects) para armazenamento e persistência de dados seguros numa base de dados MySQL.

## Funcionalidades
* **Autenticação:** Registo de utilizadores, login e proteção de rotas por sessão.
* **Painel:** Resumo dinâmico em tempo real com o total de livros, leitores, empréstimos ativos e alerta de atrasos (via consultas cruzadas/JOINs).
* **Livros:** Gestão completa do acervo (CRUD) com verificação automática de disponibilidade.
* **Leitores:** Gestão de leitores com validação de limites de empréstimo.
* **Empréstimos:** Registo de requisições, cálculo automático de prazos, tratamento de atrasos e controlo de devoluções.

## Requisitos
* PHP 8.0 ou superior.
* Servidor Web (Apache através do XAMPP).
* Servidor de Base de Dados (MySQL  através do XAMPP).
* Composer (Gestor de dependências do PHP).

## Como Executar
1. Clone este repositório na pasta raiz do seu servidor web (ex: pasta `htdocs` no XAMPP).
2. Ligue os serviços **Apache** e **MySQL** no painel de controle do XAMPP.
3. Procure no navegador o gerenciador de base de dados (ex: `http://localhost/phpmyadmin`) e crie uma nova base de dados chamada `bibliotecaphp`.
4. Importe a base de dados `bibliotecaphp.sql` fornecido no projeto para criar a estrutura de tabelas e inserir os dados iniciais de teste.
5. Abra o terminal na pasta raiz do projeto e instale as dependências executando o comando:
   ```bash
   composer install
6. Aceda ao projeto através do navegador web (ex: `http://localhost/login` - ajuste a porta ou o caminho consoante o seu ambiente local).

## BUGS
* **VIEWS.PHP** Pode acontecer um bug com variaveis ao acessar a pastas views pelo vscode pode acontecer de mostrar que as variaveis não estao sendo iniciadas, porem é apenas um bug funcionam da mesma maneirda
