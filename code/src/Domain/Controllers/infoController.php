<?php

namespace Geekbrains\Application1\Domain\Controllers;

use Geekbrains\Application1\Application\Render;
use Geekbrains\Application1\Domain\Models\SiteInfo;

class infoController
{
    public function actionIndex(): string
    {
        $siteInfo = new SiteInfo();
        //$info = $siteInfo->getInfo();
        $render = new Render();
        return $render->renderPage('site-info.twig', [
            "server" => $siteInfo->getWebServer(),
            "phpVersion" => $siteInfo->getPhpVersion(),
            "userAgent" => $siteInfo->getUserAgent(),
        ]);
    }

}