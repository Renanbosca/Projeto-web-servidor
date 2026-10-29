<?php
require_once __DIR__ . '/bd.models.php';
require_once __DIR__ . '/crudpadrao.models.php';

class Leitor implements CrudPadrao {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $emprestimos_ativos;

    public function getId() { return $this->id; }
    public function getNome() { return $this->nome; }
    public function getEmail() { return $this->email; }
    public function getTelefone() { return $this->telefone; }
    public function getEmprestimosAtivos() { return (int) $this->emprestimos_ativos; }

    public function setId($id) { $this->id = $id; }
    public function setNome($nome) { $this->nome = $nome; }
    public function setEmail($email) { $this->email = strtolower($email); }
    public function setTelefone($telefone) { 
        $this->telefone = str_replace(['(', ')', '-', ' '], '', $telefone); 
    }

    public function salvar() {
        $bd = BD::conectar();
        if (empty($this->id)) {
            $sql = $bd->prepare("INSERT INTO leitores (nome, email, telefone) VALUES (:nome, :email, :telefone)");
        } else {
            $sql = $bd->prepare("UPDATE leitores SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id");
            $sql->bindParam(':id', $this->id);
        }
        $sql->bindParam(':nome', $this->nome);
        $sql->bindParam(':email', $this->email);
        $sql->bindParam(':telefone', $this->telefone);
        $sql->execute();
    }

    public static function excluir(int $id) {
    $bd = BD::conectar();

    $sql = $bd->prepare("SELECT COUNT(*) FROM emprestimos WHERE leitor_id = :id AND data_devolucao IS NULL");
    $sql->bindParam(':id', $id);
    $sql->execute();
    if ($sql->fetchColumn() > 0) {
        throw new Exception('Este leitor está com livros emprestados e não pode ser excluído.');
    }

    $sql = $bd->prepare("SELECT COUNT(*) FROM emprestimos WHERE leitor_id = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    if ($sql->fetchColumn() > 0) {
        throw new Exception('Este leitor possui histórico de empréstimos e não pode ser excluído.');
    }

    $sql = $bd->prepare("DELETE FROM leitores WHERE id = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
}

    public static function listarTodos() {
        $bd = BD::conectar();

        $query = "SELECT l.*, 
                  (SELECT COUNT(*) FROM emprestimos e WHERE e.leitor_id = l.id AND e.data_devolucao IS NULL) as emprestimos_ativos 
                  FROM leitores l ORDER BY l.nome";
        $sql = $bd->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_CLASS, 'Leitor'); 
    }

    public static function buscarPorId(int $id) {
        $bd = BD::conectar();

        $sql = $bd->prepare("SELECT * FROM leitores WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->setFetchMode(PDO::FETCH_CLASS, 'Leitor');
        $sql->execute();
        $leitor = $sql->fetch();
        return $leitor ? $leitor : null;
    }

    public static function buscarLeitorPorEmail(string $email) {
        $bd = BD::conectar();

        $sql = $bd->prepare("SELECT * FROM leitores WHERE email = :email");
        $emailMinusculo = strtolower($email);
        $sql->bindParam(':email', $emailMinusculo);
        $sql->setFetchMode(PDO::FETCH_CLASS, 'Leitor');
        $sql->execute();
        $leitor = $sql->fetch();
        return $leitor ? $leitor : null;
    }
}