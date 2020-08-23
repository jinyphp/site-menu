<?php
$Autoload = require "../vendor/autoload.php";
$Sess = \jiny\session_start();

$Menu = \jiny\menu();
$m = $Menu->json("../data/menu/nav.json")->get();
// print_r($m);

echo $Menu->html()->ul($m);