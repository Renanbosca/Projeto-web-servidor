<?php

date_default_timezone_set('America/Sao_Paulo');

function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function campoPost(string $nome, bool $aparar = true): string
{
    $valor = $_POST[$nome] ?? '';

    if (!is_string($valor)) {
        return '';
    }
    return $aparar ? trim($valor) : $valor;
}

function redirecionar(string $url): void
{
    header("Location: $url");
    exit;
}

function definirMensagem(string $tipo, string $texto): void
{
    $_SESSION['mensagem'] = ['tipo' => $tipo, 'texto' => $texto];
}

function pegarMensagem(): ?array
{
    $mensagem = $_SESSION['mensagem'] ?? null;
    unset($_SESSION['mensagem']);
    return $mensagem;
}

function tamanhoTexto(string $texto): int
{
    return strlen($texto);
}

function converterData(string $texto): ?DateTime
{
    $data = DateTime::createFromFormat('!d/m/Y', $texto);

    if ($data === false || $data->format('d/m/Y') !== $texto) {
        return null;
    }
    return $data;
}

function formatarData(?string $data): string
{
    if (empty($data)) {
        return '-';
    }
    return date('d/m/Y', strtotime($data));
}

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
