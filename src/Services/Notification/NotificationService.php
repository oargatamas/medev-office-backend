<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 09. 10.
 * Time: 12:36
 */

namespace MedevOffice\Services\Notification;


use MedevAuth\Services\Auth\OAuth\APIProtection\Service\OAuthProtectedTwigAPIService;
use Slim\App;

class NotificationService extends OAuthProtectedTwigAPIService
{

    /**
     * @param App $app
     */
    protected function registerRoutes(App $app)
    {

    }


    protected function getTemplatePath()
    {
        return __DIR__."/View";
    }
}