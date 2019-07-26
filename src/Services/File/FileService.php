<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 9:51
 */

namespace MedevOffice\Services\File;


use MedevOffice\Services\File\Actions\Api\Folder\GetFolderContent;
use MedevSlim\Core\Service\APIService;
use Slim\App;

class FileService extends APIService
{
    const FOLDER_ID = "folderId";

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        return "FileManagementService";
    }

    /**
     * @param App $app
     * @throws \Exception
     */
    protected function registerRoutes(App $app)
    {
        $app->get("/folder/{".self::FOLDER_ID."}/content", new GetFolderContent($this));
    }
}