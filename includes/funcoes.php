<?php
/*
 * funcoes.php
 * Funções auxiliares usadas pelos controllers e pelas views.
 */

// Fuso horário usado nas datas dos empréstimos
date_default_timezone_set('America/Sao_Paulo');

/**
 * Escapa um texto antes de exibi-lo no HTML.
 * Impede que algo digitado pelo usuário (ex.: <script>) seja interpretado pelo navegador.
 */
function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/**
 * Lê um campo enviado pelo formulário (POST).
 * Remove espaços das pontas (exceto se $aparar for false, como nas senhas).
 * Se o campo não veio, ou veio num formato inesperado (ex.: um array, em um
 * formulário adulterado), devolve texto vazio em vez de gerar erro.
 */
function campoPost(string $nome, bool $aparar = true): string
{
    $valor = $_POST[$nome] ?? '';

    if (!is_string($valor)) {
        return '';
    }
    return $aparar ? trim($valor) : $valor;
}

/**
 * Redireciona o navegador para outra página e encerra o script.
 * O exit é obrigatório: sem ele o PHP continuaria executando o resto do arquivo.
 */
function redirecionar(string $url): void
{
    header("Location: $url");
    exit;
}

/**
 * Guarda na sessão uma mensagem para ser exibida na próxima página
 * (usado depois de um redirecionamento). $tipo: 'sucesso' ou 'erro'.
 */
function definirMensagem(string $tipo, string $texto): void
{
    $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
}

/**
 * Devolve a mensagem guardada na sessão (ou null) e a apaga,
 * para que ela apareça uma única vez.
 */
function pegarMensagem(): ?array
{
    $mensagem = $_SESSION['mensagem'] ?? null;
    unset($_SESSION['mensagem']);
    return $mensagem;
}

/**
 * Quantidade de caracteres de um texto (letras acentuadas contam como 1).
 * mb_strlen depende da extensão mbstring; se ela não estiver ativa, usa strlen.
 */
function tamanhoTexto(string $texto): int
{
    return function_exists('mb_strlen') ? mb_strlen($texto) : strlen($texto);
}

/**
 * Converte uma data digitada como DD/MM/AAAA em um objeto DateTime.
 * Retorna null se o formato estiver errado ou se a data não existir (ex.: 31/02/2026).
 */
function converterData(string $texto): ?DateTime
{
    // O ! zera o horário (00:00:00), para comparar só a data
    $data = DateTime::createFromFormat('!d/m/Y', $texto);

    // createFromFormat aceita 31/02 e "pula" para março; conferir o texto de volta evita isso
    if ($data === false || $data->format('d/m/Y') !== $texto) {
        return null;
    }
    return $data;
}

/**
 * Converte uma data salva como AAAA-MM-DD para exibição (DD/MM/AAAA).
 */
function formatarData(?string $data): string
{
    if (empty($data)) {
        return '-';
    }
    return date('d/m/Y', strtotime($data));
}

/**
 * Aplica a máscara de telefone aos dígitos: (42) 99999-8888 ou (42) 3222-1111.
 */
function formatarTelefone(string $digitos): string
{
    if (strlen($digitos) === 11) {
        return '(' . substr($digitos, 0, 2) . ') ' . substr($digitos, 2, 5) . '-' . substr($digitos, 7);
    }
    if (strlen($digitos) === 10) {
        return '(' . substr($digitos, 0, 2) . ') ' . substr($digitos, 2, 4) . '-' . substr($digitos, 6);
    }
    return $digitos;
}

/**
 * Usadas nas views para destacar um campo com erro de validação.
 * $erros é o array ['campo' => 'mensagem'] montado pelo controller.
 */
function classeErro(array $erros, string $campo): string
{
    return isset($erros[$campo]) ? 'invalido' : '';
}

function mensagemErro(array $erros, string $campo): string
{
    if (!isset($erros[$campo])) {
        return '';
    }
    return '<span class="erro-campo">' . e($erros[$campo]) . '</span>';
}
