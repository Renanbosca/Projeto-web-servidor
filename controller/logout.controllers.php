<?php

session_start();

$_SESSION = [];     
session_destroy();  

header('Location: /biblioteca/login?saiu=1');
exit;
