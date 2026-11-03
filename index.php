<?php
require "vendor/autoload.php";



$rotas = [
    "/login" => "controller/index.controllers.php",
    "/cadastro" => "controller/cadastro_usuario.controllers.php",
    "/painel" => "controller/painel.controllers.php",
    "/livros" => "controller/livros.controllers.php",
    "/leitores" => "controller/leitores.controllers.php",
    "/emprestimos" => "controller/emprestimos.controllers.php",
    "/sair" => "controller/logout.controllers.php"
];


$urlCompleta = $_SERVER["REQUEST_URI"];
$url = str_replace('/biblioteca', '', $urlCompleta); 
$url = explode('?', $url)[0]; 


if(array_key_exists($url, $rotas)) {

    require($rotas[$url]);
} else {

    http_response_code(404);
    echo "Erro 404! Página não existe "; 
}