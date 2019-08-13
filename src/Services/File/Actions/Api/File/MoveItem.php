<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 12:09
 */

namespace MedevOffice\Services\File\Actions\Api\File;


use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderMeta;
use MedevOffice\Services\File\Actions\Repository\Folder\MoveItemToFolder;
use MedevOffice\Services\File\Actions\Repository\Permission\ValidatePermission;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class MoveItem extends APIServlet implements PermissionRestricted
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
        $requester = $authToken->getUser()->getIdentifier();
        $itemId = $args[OfficeFileService::FILE_ID];
        $folderId = $args[OfficeFileService::FOLDER_ID];

        $getFolderInfo = (new GetFolderMeta($this->service));
        $folderInfo = $getFolderInfo->handleRequest([GetFolderMeta::FOLDER_ID => $itemId, GetFolderMeta::REQUESTER => $requester]);

        (new ValidatePermission($this->service))->handleRequest([
            ValidatePermission::ITEM_PERMISSIONS => $folderInfo->getPermissions(),
            ValidatePermission::PERMISSIONS => [Permission::READ, Permission::CREATE],
            ValidatePermission::THROW_ERROR => true
        ]);

        (new MoveItemToFolder($this->service))->handleRequest([
            MoveItemToFolder::ITEM_ID => $itemId,
            MoveItemToFolder::FOLDER_ID => $folderId
        ]);

        $data = [
            "state" => "success",
            "fileId" => $itemId,
            "newParent" => $folderId
        ];

        return $response->withJson($data,200);
    }

    /**
     * @return string[]
     */
    public static function getPermissionCodes()
    {
        //Only the movable item permission will be checked by this logic
        //new parent folder create and read  permissions will be validated in the action body
        return [
            Permission::READ,
            Permission::MOVE
        ];
    }
}