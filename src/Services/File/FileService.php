<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 9:51
 */

namespace Services\File;


use MedevSlim\Core\Service\APIService;
use Slim\App;

class FileService extends APIService
{

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        return "FileManagementService";
    }

    /**
     * @param App $app
     */
    protected function registerRoutes(App $app)
    {
        // TODO: Implement registerRoutes() method.
    }
}