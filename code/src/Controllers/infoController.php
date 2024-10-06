<?php

namespace Geekbrains\Application1\Controllers;

use Geekbrains\Application1\Models\SiteInfo;
use Geekbrains\Application1\Render;

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