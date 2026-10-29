<?php
interface CrudPadrao {
    public function salvar();
    public static function excluir(int $id);
    public static function listarTodos();
    public static function buscarPorId(int $id);
}