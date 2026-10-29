<?php

session_start();

$_SESSION = [];     
session_destroy();  

header('Location: index.models.php?saiu=1');
exit;
