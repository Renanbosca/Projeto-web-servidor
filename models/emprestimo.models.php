<?php
require_once __DIR__ . '/bd.models.php';
require_once __DIR__ . '/crudpadrao.models.php';
require_once __DIR__ . '/livro.models.php';
require_once __DIR__ . '/leitor.models.php';

class Emprestimo implements CrudPadrao {
    const LIMITE_EMPRESTIMOS_POR_LEITOR = 3;
    const PRAZO_MAXIMO_DIAS = 30;

    private $id;
    private $livro_id;
    private $leitor_id;
    private $data_emprestimo;
    private $data_prevista;
    private $data_devolucao;
    private $livro_titulo;
    private $leitor_nome;
    private $leitor_telefone;

    public function getId() { return $this->id; }
    public function getLivroId() { return $this->livro_id; }
    public function getLeitorId() { return $this->leitor_id; }
    public function getDataEmprestimo() { return $this->data_emprestimo; }
    public function getDataPrevista() { return $this->data_prevista; }
    public function getDataDevolucao() { return $this->data_devolucao; }
    public function getLivroTitulo() { return $this->livro_titulo; }
    public function getLeitorNome() { return $this->leitor_nome; }
    public function getLeitorTelefone() { return $this->leitor_telefone; }

    public function setId($id) { $this->id = $id; }
    public function setLivroId($id) { $this->livro_id = $id; }
    public function setLeitorId($id) { $this->leitor_id = $id; }
    public function setDataEmprestimo($d) { $this->data_emprestimo = $d; }
    public function setDataPrevista($d) { $this->data_prevista = $d; }
    public function setDataDevolucao($d) { $this->data_devolucao = $d; }

    public function getStatus(): string {
        if ($this->data_devolucao !== null) return 'devolvido';
        if ($this->data_prevista < date('Y-m-d')) return 'atrasado';
        return 'em_dia';
    }

    public function getDiasAtraso(): int {
        $prevista = new DateTime($this->data_prevista);
        $fim = new DateTime($this->data_devolucao ?? 'today');
        return ($fim <= $prevista) ? 0 : $prevista->diff($fim)->days;
    }

    public function salvar() {
        $bd = BD::conectar();
        
        if (empty($this->id)) {
            $sql = $bd->prepare("INSERT INTO emprestimos (livro_id, leitor_id, data_emprestimo, data_prevista) VALUES (:livro_id, :leitor_id, :data_emprestimo, :data_prevista)");
            $sql->bindParam(':livro_id', $this->livro_id);
            $sql->bindParam(':leitor_id', $this->leitor_id);
            $sql->bindParam(':data_emprestimo', $this->data_emprestimo);
            $sql->bindParam(':data_prevista', $this->data_prevista);
        } else {
            $sql = $bd->prepare("UPDATE emprestimos SET data_devolucao = :data_devolucao WHERE id = :id");
            $sql->bindParam(':id', $this->id);
            $sql->bindParam(':data_devolucao', $this->data_devolucao);
        }
        
        $sql->execute();
    }

    public static function excluir(int $id) {
        $bd = BD::conectar();
        $sql = $bd->prepare("DELETE FROM emprestimos WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();
    }

    public static function buscarPorId(int $id) {
        $bd = BD::conectar();
        $sql = $bd->prepare("SELECT * FROM emprestimos WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->setFetchMode(PDO::FETCH_CLASS, 'Emprestimo');
        $sql->execute();
        $emp = $sql->fetch();
        return $emp ? $emp : null;
    }

    public static function listarTodos() {
        return self::listarEmprestimos('todos');
    }

    public static function listarEmprestimos(string $filtro = 'todos'): array {
        $bd = BD::conectar();
        $where = "1=1";
        if ($filtro === 'ativos') $where = "e.data_devolucao IS NULL";
        elseif ($filtro === 'atrasados') $where = "e.data_devolucao IS NULL AND e.data_prevista < CURDATE()";
        elseif ($filtro === 'devolvidos') $where = "e.data_devolucao IS NOT NULL";

        $query = "SELECT e.*, l.titulo as livro_titulo, le.nome as leitor_nome, le.telefone as leitor_telefone 
                  FROM emprestimos e
                  LEFT JOIN livros l ON e.livro_id = l.id
                  LEFT JOIN leitores le ON e.leitor_id = le.id
                  WHERE $where ORDER BY e.data_prevista ASC";

        $sql = $bd->prepare($query);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_CLASS, 'Emprestimo');
    }

    public static function registrar(int $livroId, int $leitorId, DateTime $dataPrevista) {
        $bd = BD::conectar();
        
        $q1 = $bd->prepare("SELECT id FROM emprestimos WHERE livro_id = ? AND data_devolucao IS NULL");
        $q1->execute([$livroId]);
        if ($q1->fetch()) throw new Exception('O livro já está emprestado.');
        
        $q2 = $bd->prepare("SELECT id FROM emprestimos WHERE leitor_id = ? AND data_devolucao IS NULL AND data_prevista < CURDATE()");
        $q2->execute([$leitorId]);
        if ($q2->fetch()) throw new Exception('O leitor tem empréstimos em atraso e não pode requisitar novos livros.');
        
        $q3 = $bd->prepare("SELECT COUNT(*) FROM emprestimos WHERE leitor_id = ? AND data_devolucao IS NULL");
        $q3->execute([$leitorId]);
        if ($q3->fetchColumn() >= self::LIMITE_EMPRESTIMOS_POR_LEITOR) {
            throw new Exception('O leitor já atingiu o limite máximo de livros.');
        }

        $emp = new Emprestimo();
        $emp->setLivroId($livroId);
        $emp->setLeitorId($leitorId);
        $emp->setDataEmprestimo(date('Y-m-d'));
        $emp->setDataPrevista($dataPrevista->format('Y-m-d'));
        $emp->salvar();
    }

    public static function registrarDevolucao(int $id) {
        $emp = self::buscarPorId($id);
        if ($emp === null) throw new Exception('Empréstimo não encontrado.');
        if ($emp->getDataDevolucao() !== null) throw new Exception('A devolução já foi registrada.');
        $emp->setDataDevolucao(date('Y-m-d'));
        $emp->salvar();
        return $emp;
    }
}