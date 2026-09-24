<?php
/*
 * dados_exemplo.php (opcional)
 *
 * Preenche o sistema com dados de exemplo para testes e para a apresentação:
 * um usuário de acesso, alguns livros, leitores e empréstimos (incluindo um atrasado).
 *
 * Uso, pelo terminal, na pasta do projeto:
 *     php scripts/dados_exemplo.php
 *
 * Só funciona com o sistema vazio, para não misturar com dados reais.
 */
if (PHP_SAPI !== 'cli') {
    exit('Execute este script pelo terminal: php scripts/dados_exemplo.php');
}

require_once __DIR__ . '/../includes/funcoes.php';
require_once __DIR__ . '/../models/usuario.php';
require_once __DIR__ . '/../models/emprestimo.php';

try {
    if (!empty(lerRegistros('usuarios')) || !empty(lerRegistros('livros'))) {
        exit("O sistema já tem dados. Apague os arquivos .json da pasta data/ para recomeçar.\n");
    }

    cadastrarUsuario('Administrador', 'admin@biblioteca.com', 'admin123');

    $livros = [
        ['Dom Casmurro', 'Machado de Assis', 1899],
        ['O Cortiço', 'Aluísio Azevedo', 1890],
        ['Vidas Secas', 'Graciliano Ramos', 1938],
        ['A Hora da Estrela', 'Clarice Lispector', 1977],
        ['Capitães da Areia', 'Jorge Amado', 1937],
        ['Grande Sertão: Veredas', 'João Guimarães Rosa', 1956],
    ];
    foreach ($livros as [$titulo, $autor, $ano]) {
        salvarLivro(['id' => '', 'titulo' => $titulo, 'autor' => $autor, 'ano' => $ano]);
    }

    salvarLeitor(['id' => '', 'nome' => 'Ana Souza', 'email' => 'ana@email.com', 'telefone' => '42999991111']);
    salvarLeitor(['id' => '', 'nome' => 'Bruno Lima', 'email' => 'bruno@email.com', 'telefone' => '4232221234']);
    salvarLeitor(['id' => '', 'nome' => 'Carla Mendes', 'email' => 'carla@email.com', 'telefone' => '42988887777']);

    // Empréstimo em dia (livro 1 com a Ana)
    registrarEmprestimo(1, 1, new DateTime('today +10 days'));

    // Empréstimo atrasado (livro 2 com o Bruno): gravado direto com datas no passado,
    // porque registrarEmprestimo() não aceita data prevista anterior a hoje
    salvarRegistro('emprestimos', [
        'id'              => null,
        'livro_id'        => 2,
        'leitor_id'       => 2,
        'data_emprestimo' => date('Y-m-d', strtotime('-20 days')),
        'data_prevista'   => date('Y-m-d', strtotime('-5 days')),
        'data_devolucao'  => null,
    ]);

    // Empréstimo já devolvido (livro 3 com a Carla)
    salvarRegistro('emprestimos', [
        'id'              => null,
        'livro_id'        => 3,
        'leitor_id'       => 3,
        'data_emprestimo' => date('Y-m-d', strtotime('-15 days')),
        'data_prevista'   => date('Y-m-d', strtotime('-2 days')),
        'data_devolucao'  => date('Y-m-d', strtotime('-3 days')),
    ]);

    echo "Dados de exemplo criados.\n";
    echo "Login: admin@biblioteca.com  |  Senha: admin123\n";
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage() . "\n";
}
