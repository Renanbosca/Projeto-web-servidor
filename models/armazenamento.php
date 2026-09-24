<?php

const PASTA_DADOS = __DIR__ . '/../data';

function lerRegistros(string $nome): array
{
    $caminho = PASTA_DADOS . "/$nome.json";

    if (!file_exists($caminho)) {
        return [];
    }

    $conteudo = file_get_contents($caminho);
    if ($conteudo === false) {
        throw new Exception("Não foi possível ler o arquivo data/$nome.json.");
    }

    $registros = json_decode($conteudo, true);
    if (!is_array($registros)) {
        throw new Exception("O arquivo data/$nome.json está corrompido.");
    }

    return $registros;
}

function gravarRegistros(string $nome, array $registros): void
{
    $json = json_encode(array_values($registros), JSON_PRETTY_PRINT);

    if (file_put_contents(PASTA_DADOS . "/$nome.json", $json) === false) { 
        throw new Exception('Não foi possível salvar os dados.');
    }
}

function proximoId(array $registros): int
{
    $maior = 0;
    foreach ($registros as $registro) {
        if ($registro['id'] > $maior) {
            $maior = $registro['id'];
        }
    }
    return $maior + 1;
}

function buscarPorId(array $registros, int $id): ?array
{
    foreach ($registros as $registro) {
        if ($registro['id'] === $id) {
            return $registro;
        }
    }
    return null;
}

function salvarRegistro(string $nome, array $registro): array
{
    $registros = lerRegistros($nome);

    if (empty($registro['id'])) {
        $registro['id'] = proximoId($registros);
        $registros[] = $registro;
    } else {
        $encontrado = false;
        foreach ($registros as $indice => $atual) {
            if ($atual['id'] === $registro['id']) {
                $registros[$indice] = $registro;
                $encontrado = true;
            }
        }
        if (!$encontrado) {
            throw new Exception('O registro não foi encontrado.');
        }
    }

    gravarRegistros($nome, $registros);
    return $registro;
}

function excluirRegistro(string $nome, int $id): void
{
    $registros = lerRegistros($nome);
    $restantes = [];

    foreach ($registros as $registro) {
        if ($registro['id'] !== $id) {
            $restantes[] = $registro;
        }
    }

    if (count($restantes) === count($registros)) {
        throw new Exception('Registro não encontrado. Ele pode já ter sido excluído.');
    }

    gravarRegistros($nome, $restantes);
}
