<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 9:51
 */

namespace MedevOffice\Services\File;


use MedevAuth\Services\Auth\OAuth\APIProtection\Service\OAuthProtectedAPIService;
use MedevOffice\Services\File\Actions\Api\FIle\MoveItem;
use MedevOffice\Services\File\Actions\Api\Folder\DownloadFile;
use MedevOffice\Services\File\Actions\Api\Folder\GetFolderContent;
use MedevOffice\Services\File\Actions\Api\Folder\UploadFileToFolder;
use MedevOffice\Services\File\Actions\Api\Permission\GrantPermission;
use MedevOffice\Services\File\Actions\Api\Permission\RemovePermission;
use MedevOffice\Services\File\Middleware\PermissionChecker;
use MedevSlim\Core\Application\MedevApp;
use MedevSlim\Core\Service\APIService;
use Slim\App;

class FileService extends OAuthProtectedAPIService
{
    const FOLDER_ID = "folderId";
    const FILE_ID = "file_id";

    const ROUTE_DOWNLOAD_FILE = "downloadFile";
    const ROUTE_GET_FOLDER_CONTENT = "getfolderContent";
    const ROUTE_UPLOAD_FILE = "uploadFile";
    const ROUTE_MOVE_ITEM = "moveItem";
    const ROUTE_GRANT = "grant";
    const ROUTE_REMOVE_GRANT = "removeGrant";


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
            ->add(new PermissionChecker($this,DownloadFile::getPermissionCodes()))
            ->setName(self::ROUTE_DOWNLOAD_FILE);

        $app->get("/folder/{".self::FOLDER_ID."}/content", new GetFolderContent($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->setName(self::ROUTE_GET_FOLDER_CONTENT);

        $app->post("/folder/{".self::FOLDER_ID."}/file", new UploadFileToFolder($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->setName(self::ROUTE_UPLOAD_FILE);

        $app->post("/move/{".self::FILE_ID."}/to/{".self::FOLDER_ID."}", new MoveItem($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,DownloadFile::getPermissionCodes()))
            ->setName(self::ROUTE_MOVE_ITEM);

        $app->post("/{".self::FILE_ID."}/permission", new GrantPermission($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,GrantPermission::getPermissionCodes()))
            ->setName(self::ROUTE_GRANT);

        $app->delete("/{".self::FILE_ID."}/permission", new RemovePermission($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,RemovePermission::getPermissionCodes()))
            ->setName(self::ROUTE_REMOVE_GRANT);
    }
}