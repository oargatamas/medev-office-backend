<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 9:51
 */

namespace MedevOffice\Services\File;


use MedevOffice\Services\File\Actions\Api\Folder\DownloadFile;
use MedevOffice\Services\File\Actions\Api\Folder\GetFolderContent;
use MedevOffice\Services\File\Actions\Api\Folder\UploadFileToFolder;
use MedevSlim\Core\Service\APIService;
use Slim\App;

class FileService extends APIService
{
    const FOLDER_ID = "folderId";
    const FILE_ID = "file_id";

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
        $app->get("/file/{".self::FILE_ID."}/data", new DownloadFile($this))
            ->setName($this->getServiceName());

        $app->get("/folder/{".self::FOLDER_ID."}/content", new GetFolderContent($this))
            ->setName($this->getServiceName());

        $app->post("/folder/{".self::FOLDER_ID."}/file", new UploadFileToFolder($this))
            ->setName($this->getServiceName());
    }
}