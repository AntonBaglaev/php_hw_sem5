<?php

namespace Geekbrains\Application1\Domain\Controllers;
use Geekbrains\Application1\Application\Render;

class PageController {

    public function actionIndex() {
        $render = new Render();
//        echo Application::config()["storage"]["address"];
        
        return $render->renderPage('page-index.twig', ['title' => 'Главная страница']);
    }
}