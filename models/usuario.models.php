<?php
require_once __DIR__ . '/bd.models.php';

class Usuario {

    private $id;
    private $nome;
    private $email;
    private $senha;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getSenha() { return $this->senha; }

    public static function buscarUsuarioPorEmail(string $email) {
        $bd = BD::conectar();
        $sql = $bd->prepare("SELECT * FROM usuario WHERE email = :email");
        $sql->bindParam(':email', $email);
        $sql->execute();
        

        $sql->setFetchMode(PDO::FETCH_CLASS, 'Usuario');
        $usuario = $sql->fetch();
        
        return $usuario ? $usuario : null;
    }

    public static function cadastrarUsuario(string $nome, string $email, string $senha) {
        if (self::buscarUsuarioPorEmail($email) !== null) {
            throw new Exception('Já existe uma conta com este e-mail.');
        }
        $bd = BD::conectar();
        $sql = $bd->prepare("INSERT INTO usuario (nome, email, senha) VALUES (:nome, :email, :senha)");
        $sql->bindParam(':nome', $nome);
        $sql->bindParam(':email', $email);
        $sql->bindParam(':senha', $senha);
        $sql->execute();
    }

    public static function autenticarUsuario(string $email, string $senha) {
        $usuario = self::buscarUsuarioPorEmail($email);
        
        if ($usuario !== null && $senha === $usuario->getSenha()) {
            return $usuario;
        }
        return null;
    }
}