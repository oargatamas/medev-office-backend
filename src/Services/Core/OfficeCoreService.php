<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 01.
 * Time: 16:07
 */

namespace MedevOffice\Services\Core;


use MedevAuth\Services\Auth\OAuth\APIProtection\Service\OAuthProtectedAPIService;
use MedevOffice\Services\Core\Actions\Api\GetUserApplications;
use MedevSlim\Core\Service\APIService;
use Slim\App;

class OfficeCoreService extends OAuthProtectedAPIService
{
    const ROUTE_USER_APPS = "getUserApplications";

    /**
     * @param App $app
     * @throws \Exception
     */
    protected function registerRoutes(App $app)
    {
        $app->get("/applications", new GetUserApplications($this))
            ->setArgument(APIService::SERVICE_ID, $this->getServiceName())
            ->setName(self::ROUTE_USER_APPS);
    }
}