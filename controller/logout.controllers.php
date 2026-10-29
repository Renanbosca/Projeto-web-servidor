<?php

session_start();

$_SESSION = [];     
session_destroy();  

header('Location: index.controllers.php?saiu=1');
exit;
