<?php
/*
 * usuario.php (Model de Usuários)
 *
 * Usuários são os funcionários da biblioteca que acessam o sistema.
 * Campos: id, nome, email, senha (hash).
 */
require_once __DIR__ . '/armazenamento.php';

/**
 * Procura um usuário pelo e-mail (sem diferenciar maiúsculas/minúsculas).
 */
function buscarUsuarioPorEmail(string $email): ?array
{
    foreach (lerRegistros('usuarios') as $usuario) {
        if ($usuario['email'] === strtolower($email)) {
            return $usuario;
        }
    }
    return null;
}

/**
 * Cadastra um novo usuário.
 * A senha nunca é salva como texto: password_hash gera um hash que só pode
 * ser conferido depois com password_verify.
 */
function cadastrarUsuario(string $nome, string $email, string $senha): array
{
    // Regra de negócio: não pode haver dois usuários com o mesmo e-mail
    if (buscarUsuarioPorEmail($email) !== null) {
        throw new Exception('Já existe uma conta com este e-mail.');
    }

    return salvarRegistro('usuarios', [
        'id'    => null,
        'nome'  => $nome,
        'email' => strtolower($email),
        'senha' => password_hash($senha, PASSWORD_DEFAULT),
    ]);
}

/**
 * Confere e-mail e senha. Retorna o usuário se estiverem corretos; senão, null.
 */
function autenticarUsuario(string $email, string $senha): ?array
{
    $usuario = buscarUsuarioPorEmail($email);

    if ($usuario !== null && password_verify($senha, $usuario['senha'])) {
        return $usuario;
    }
    return null;
}
