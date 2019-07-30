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
use MedevSlim\Core\Application\MedevApp;
use MedevSlim\Core\Service\APIService;
use Slim\App;

class FileService extends APIService
{
    const FOLDER_ID = "folderId";
    const FILE_ID = "file_id";

    const ROUTE_DOWNLOAD_FILE = "downloadFile";
    const ROUTE_GET_FOLDER_CONTENT = "getfolderContent";
    const ROUTE_UPLOAD_FILE = "uploadFile";


    public function __construct(MedevApp $app)
    {
        parent::__construct($app);
    }

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
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->setName(self::ROUTE_DOWNLOAD_FILE);

        $app->get("/folder/{".self::FOLDER_ID."}/content", new GetFolderContent($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->setName(self::ROUTE_GET_FOLDER_CONTENT);

        $app->post("/folder/{".self::FOLDER_ID."}/file", new UploadFileToFolder($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->setName(self::ROUTE_UPLOAD_FILE);
    }
}