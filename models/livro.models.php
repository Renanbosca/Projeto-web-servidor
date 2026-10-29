<?php
require_once __DIR__ . '/bd.models.php';
require_once __DIR__ . '/crudpadrao.models.php';

class Livro implements CrudPadrao {
    private $id;
    private $titulo;
    private $autor;
    private $ano;
    private $disponivel; 

    public function getId() { return $this->id; }
    public function getTitulo() { return $this->titulo; }
    public function getAutor() { return $this->autor; }
    public function getAno() { return $this->ano; }
    public function estaDisponivel() { return (bool) $this->disponivel; }

    public function setId($id) { $this->id = $id; }
    public function setTitulo($t) { $this->titulo = $t; }
    public function setAutor($a) { $this->autor = $a; }
    public function setAno($ano) { $this->ano = $ano; }

    public function salvar() {
        $bd = BD::conectar();
        if (empty($this->id)) {
            $sql = $bd->prepare("INSERT INTO livros (titulo, autor, ano) VALUES (:titulo, :autor, :ano)");
        } else {
            $sql = $bd->prepare("UPDATE livros SET titulo = :titulo, autor = :autor, ano = :ano WHERE id = :id");
            $sql->bindParam(':id', $this->id); 
        }
        $sql->bindParam(':titulo', $this->titulo);
        $sql->bindParam(':autor', $this->autor);
        $sql->bindParam(':ano', $this->ano);
        $sql->execute();
    }

    public static function excluir(int $id) {
    $bd = BD::conectar();

    $sql = $bd->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = :id AND data_devolucao IS NULL");
    $sql->bindParam(':id', $id);
    $sql->execute();
    if ($sql->fetchColumn() > 0) {
        throw new Exception('Este livro está emprestado e não pode ser excluído. Registre a devolução primeiro.');
    }

    $sql = $bd->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    if ($sql->fetchColumn() > 0) {
        throw new Exception('Este livro possui histórico de empréstimos e não pode ser excluído.');
    }

    $sql = $bd->prepare("DELETE FROM livros WHERE id = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
}

    public static function listarTodos() {
        $bd = BD::conectar();

        $query = "SELECT l.*, 
                  NOT EXISTS (SELECT 1 FROM emprestimos e WHERE e.livro_id = l.id AND e.data_devolucao IS NULL) as disponivel 
                  FROM livros l ORDER BY l.titulo";
        
        $sql = $bd->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_CLASS, 'Livro');
    }

    public static function buscarPorId(int $id) {
        $bd = BD::conectar();
        $sql = $bd->prepare("SELECT * FROM livros WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->setFetchMode(PDO::FETCH_CLASS, 'Livro');
        $sql->execute();
        $livro = $sql->fetch();
        return $livro ? $livro : null;
    }
}