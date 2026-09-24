<?php
require_once __DIR__ . '/armazenamento.php';

function buscarUsuarioPorEmail(string $email): ?array
{
    foreach (lerRegistros('usuarios') as $usuario) {
        if ($usuario['email'] === strtolower($email)) {
            return $usuario;
        }
    }
    return null;
}

function cadastrarUsuario(string $nome, string $email, string $senha): array
{
    if (buscarUsuarioPorEmail($email) !== null) {
        throw new Exception('Já existe uma conta com este e-mail.');
    }
    return salvarRegistro('usuarios', [
        'id'    => null,
        'nome'  => $nome,
        'email' => strtolower($email),
        'senha' => $senha,
    ]);
}

function autenticarUsuario(string $email, string $senha): ?array
{
    $usuario = buscarUsuarioPorEmail($email);

    if ($usuario !== null && $senha === $usuario['senha']) {
        return $usuario;
    }
    return null;
}
