<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 9:51
 */

namespace MedevOffice\Services\File;


use MedevAuth\Services\Auth\OAuth\APIProtection\Service\OAuthProtectedAPIService;
use MedevOffice\Services\File\Actions\Api\DeleteItem;
use MedevOffice\Services\File\Actions\Api\File\EditFile;
use MedevOffice\Services\File\Actions\Api\File\MoveItem;
use MedevOffice\Services\File\Actions\Api\File\UploadFileToFolder;
use MedevOffice\Services\File\Actions\Api\Folder\CreateFolder;
use MedevOffice\Services\File\Actions\Api\Folder\DownloadFile;
use MedevOffice\Services\File\Actions\Api\Folder\EditFolder;
use MedevOffice\Services\File\Actions\Api\Folder\GetFolderContent;
use MedevOffice\Services\File\Actions\Api\Folder\GetRootFolder;
use MedevOffice\Services\File\Actions\Api\Permission\FetchPermissions;
use MedevOffice\Services\File\Actions\Api\Permission\GrantPermission;
use MedevOffice\Services\File\Actions\Api\Permission\RemovePermission;
use MedevOffice\Services\File\Middleware\PermissionChecker;
use MedevSlim\Core\Application\MedevApp;
use MedevSlim\Core\Service\APIService;
use Slim\App;

class OfficeFileService extends OAuthProtectedAPIService
{
    const FOLDER_ID = "folderId";
    const FILE_ID = "file_id";

    const ROUTE_DOWNLOAD_FILE = "downloadFile";
    const ROUTE_GET_ROOT_FOLDER = "getRootFolder";
    const ROUTE_GET_FOLDER_CONTENT = "getFolderContent";
    const ROUTE_UPLOAD_FILE = "uploadFile";
    const ROUTE_MOVE_ITEM = "moveItem";
    const ROUTE_GRANT = "grant";
    const ROUTE_REMOVE_GRANT = "removeGrant";
    const ROUTE_REMOVE_FILE = "removeFile";
    const ROUTE_REMOVE_FOLDER = "removeFolder";
    const ROUTE_CREATE_FOLDER = "createFolder";
    const ROUTE_EDIT_FILE = "editFile";
    const ROUTE_EDIT_FOLDER = "editFolder";
    const ROUTE_FETCH_PERMISSIONS = "fetchPermissions";


    public function __construct(MedevApp $app)
    {
        parent::__construct($app);
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

        $app->get("/folder/root", new GetRootFolder($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->setName(self::ROUTE_GET_ROOT_FOLDER);

        $app->get("/folder/{".self::FOLDER_ID."}/content", new GetFolderContent($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,GetFolderContent::getPermissionCodes()))
            ->setName(self::ROUTE_GET_FOLDER_CONTENT);

        $app->post("/folder/{".self::FOLDER_ID."}/file", new UploadFileToFolder($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,UploadFileToFolder::getPermissionCodes()))
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

        $app->delete("/folder/{".self::FILE_ID."}", new DeleteItem($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,DeleteItem::getPermissionCodes()))
            ->setName(self::ROUTE_REMOVE_FOLDER);

        $app->delete("/file/{".self::FILE_ID."}", new DeleteItem($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,DeleteItem::getPermissionCodes()))
            ->setName(self::ROUTE_REMOVE_FILE);

        $app->post("/folder/{".self::FOLDER_ID."}/folder", new CreateFolder($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,CreateFolder::getPermissionCodes()))
            ->setName(self::ROUTE_CREATE_FOLDER);

        $app->post("/file/{".self::FILE_ID."}", new EditFile($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,EditFile::getPermissionCodes()))
            ->setName(self::ROUTE_EDIT_FILE);

        $app->post("/folder/{".self::FOLDER_ID."}", new EditFolder($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->add(new PermissionChecker($this,EditFolder::getPermissionCodes()))
            ->setName(self::ROUTE_EDIT_FOLDER);

        $app->post("/permission/types", new FetchPermissions($this))
            ->setArgument(APIService::SERVICE_ID,$this->getServiceName())
            ->setName(self::ROUTE_FETCH_PERMISSIONS);
    }
}