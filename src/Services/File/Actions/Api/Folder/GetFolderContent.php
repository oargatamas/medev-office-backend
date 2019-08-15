<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 14:45
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;



use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderItems;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderMeta;
use MedevOffice\Services\File\Actions\Repository\GetAllParentOfItem;
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

        $getFolder = new GetFolderMeta($this->service);
        $folder = $getFolder->handleRequest([
            GetFolderMeta::FOLDER_ID => $folderId,
            GetFolderMeta::REQUESTER => $userId,
        ]);

        $getItemParents = new GetAllParentOfItem($this->service);
        $parents = $getItemParents->handleRequest([
            GetAllParentOfItem::ITEM_ID => $folderId,
            GetAllParentOfItem::USER_ID => $userId,
        ]);

        $getItems = new GetFolderItems($this->service);
        $items = $getItems->handleRequest([
            GetFolderItems::FOLDER_ID => $folderId,
            GetFolderItems::USER_ID => $userId
        ]);

        $data = [
            "meta" => $folder,
            "parents" => $parents,
            "content" => $items,
        ];

        return $response->withJson($data,200);
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