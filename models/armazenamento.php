<?php
/*
 * armazenamento.php (Model base)
 *
 * Acesso aos dados do sistema. Cada "tabela" é um arquivo JSON na pasta data/:
 * usuarios.json, livros.json, leitores.json e emprestimos.json.
 *
 * Cada arquivo guarda uma lista de registros (arrays associativos), e todo
 * registro tem um campo 'id' numérico.
 *
 * Banco de dados com PDO é conteúdo do Trabalho 2. Quando chegar lá, basta
 * trocar o interior destas funções e dos models; controllers e views continuam iguais.
 */

// Caminho da pasta onde ficam os arquivos JSON
const PASTA_DADOS = __DIR__ . '/../data';

/**
 * Lê todos os registros de um arquivo de dados.
 * Se o arquivo ainda não existe (sistema recém-instalado), retorna uma lista vazia.
 */
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

    // O segundo parâmetro (true) faz o json_decode devolver arrays associativos
    $registros = json_decode($conteudo, true);
    if (!is_array($registros)) {
        throw new Exception("O arquivo data/$nome.json está corrompido.");
    }

    return $registros;
}

/**
 * Grava a lista completa de registros no arquivo de dados (substitui o conteúdo anterior).
 */
function gravarRegistros(string $nome, array $registros): void
{
    // array_values renumera os índices (0, 1, 2...) para o JSON ficar sempre uma lista
    $json = json_encode(array_values($registros), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // O @ esconde o aviso padrão do PHP; o erro é tratado logo abaixo com uma mensagem clara.
    // LOCK_EX trava o arquivo durante a escrita, para duas gravações não se misturarem.
    if (@file_put_contents(PASTA_DADOS . "/$nome.json", $json, LOCK_EX) === false) {
        throw new Exception('Não foi possível salvar os dados. Verifique se a pasta data/ tem permissão de escrita.');
    }
}

/**
 * Calcula o próximo id livre: maior id existente + 1.
 */
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

/**
 * Procura um registro pelo id dentro de uma lista. Retorna null se não encontrar.
 */
function buscarPorId(array $registros, int $id): ?array
{
    foreach ($registros as $registro) {
        if ($registro['id'] === $id) {
            return $registro;
        }
    }
    return null;
}

/**
 * Insere ou atualiza um registro:
 *  - sem id (vazio, null ou 0): é um registro novo e recebe o próximo id;
 *  - com id: substitui o registro que tem esse id.
 * Retorna o registro salvo, já com o id.
 */
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
            throw new Exception('Registro não encontrado. Ele pode ter sido excluído.');
        }
    }

    gravarRegistros($nome, $registros);
    return $registro;
}

/**
 * Exclui o registro com o id informado.
 */
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
