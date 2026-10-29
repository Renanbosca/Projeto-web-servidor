<?php

class BD {
    
    public static function conectar() {
        try {
            $bd = new PDO('mysql:host=localhost;dbname=bibliotecaphp;charset=utf8mb4', 'root', '');
            
            $bd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            return $bd;
            
        } catch (PDOException $e) {
            
            throw new Exception("Erro na conexão à base de dados: " . $e->getMessage());
        }
    }
}