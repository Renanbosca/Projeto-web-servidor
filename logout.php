<?php
/*
 * logout.php
 * Encerra a sessão do usuário e volta para o login.
 */
session_start();

$_SESSION = [];     // apaga os dados da sessão
session_destroy();  // destrói a sessão no servidor

header('Location: index.php?saiu=1');
exit;
