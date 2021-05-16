<?php

require '../vendor/autoload.php';
use Api\Education;

$education = new Education();
$verb = $_SERVER['REQUEST_METHOD'];
call_user_func([$education, strtolower($verb)]);
