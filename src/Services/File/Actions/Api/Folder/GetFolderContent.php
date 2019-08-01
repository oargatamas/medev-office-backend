<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 14:45
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;



use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;
use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\Folder\GetChildFiles;
use MedevOffice\Services\File\Actions\Repository\Folder\GetChildFolders;
use MedevOffice\Services\File\Actions\Repository\Permission\GetItemPermissions;
use MedevOffice\Services\File\Actions\Repository\Permission\ValidatePermission;
use MedevOffice\Services\File\Entities\DriveEntity;
use MedevOffice\Services\File\Entities\Folder;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class GetFolderContent extends APIServlet implements PermissionRestricted
{


    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws \Exception
     */
    public function handleRequest(Request $request, Response $response, $args)
    {
        /** @var OAuthToken $authToken */
        $authToken = $request->getAttribute(OAuthService::AUTH_TOKEN);
        $userId = $authToken->getUser()->getIdentifier();
        $folderId = $args[OfficeFileService::FOLDER_ID];

        $getFolders = new GetChildFolders($this->service);
        $folders = $getFolders->handleRequest([Folder::ID => $folderId]);

        $getFiles = new GetChildFiles($this->service);
        $files = $getFiles->handleRequest([Folder::ID => $folderId]);

        /** @var DriveEntity[] $items */
        $items = array_merge($folders,$files);

        $itemIds = array_map(function(DatabaseEntity $entity){
            return $entity->getIdentifier();
        },$items);

        $getPermissions = new GetItemPermissions($this->service);
        $permissions = $getPermissions->handleRequest([
            GetItemPermissions::ITEM_ID => $itemIds,
            GetItemPermissions::USER_ID => $userId
        ]);

        foreach ($items as $item){
            $item->setPermissions($permissions[$item->getIdentifier()]);
        }

        $permissionsCheck =  new ValidatePermission($this->service);

        $filteredItems = array_filter($items,function(DriveEntity $item) use($permissionsCheck){
            return $permissionsCheck->handleRequest([
                ValidatePermission::ITEM_PERMISSIONS => $item->getPermissions(),
                ValidatePermission::PERMISSIONS => [Permission::READ]
            ]);
        });

        return $response->withJson($filteredItems);
    }

    /**
     * @return string[]
     */
    public static function getPermissionCodes()
    {
        return [
            Permission::READ
        ];
    }
}