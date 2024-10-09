<?php


require_once(__DIR__ . '/vendor/autoload.php');

use Geekbrains\Application1\Application\Application;
use Geekbrains\Application1\Application\Render;

try{
    $app = new Application();
    $result = $app->runApp();

    echo $result;
}
catch(Exception $e){
    echo Render::renderExceptionPage($e);
}