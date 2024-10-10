<?php

namespace Geekbrains\Application1\Application;

use Exception;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

class Render {

    private string $viewFolder = '/src/Domain/Views/';
    private FilesystemLoader $loader;
    private Environment $environment;



    public function __construct(){

        $this->loader = new FilesystemLoader($_SERVER['DOCUMENT_ROOT'] . $this->viewFolder);
        $this->environment = new Environment($this->loader, [
            // 'cache' => $_SERVER['DOCUMENT_ROOT'].'/cache/',
        ]);
    }

    public function renderPage(string $contentTemplateName = 'page-index.twig', array $templateVariables = []): string
    {
        $template = $this->environment->load('main.twig');

        $templateVariables['content_template_name'] = $contentTemplateName;

        $templateVariables['random_int'] = rand(1, 10000);
        $templateVariables['style'] = file_get_contents('src/Domain/Views/style.css');

        if(isset($_SESSION['auth']['user_name'])) {
            $templateVariables['user_authorized'] = true;
            $templateVariables['user_name'] = $_SESSION['auth']['user_name'];
            $templateVariables['user_lastname'] = $_SESSION['auth']['user_lastname'];

        }

        return $template->render($templateVariables);
    }

    public static function renderExceptionPage(Exception $exception): string {
        $render = new Render();
        return $render->renderPage('error.twig', ['error_message' => $exception->getMessage()]);
    }

    public function renderPageWithForm(string $contentTemplateName = 'page-index.twig', array $templateVariables = []) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $templateVariables['csrf_token'] = $_SESSION['csrf_token'];
        return $this->renderPage($contentTemplateName, $templateVariables);
    }
}