<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 13:04
 */

namespace MedevOffice\Services\File\Actions\Api\Permission;


use MedevOffice\Services\File\Actions\Repository\Permission\RemoveItemPermission;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class RemovePermission extends APIServlet implements PermissionRestricted
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
        $itemId = $args[OfficeFileService::FILE_ID];
        $requestBody = $request->getParsedBody();

        $userId = $requestBody["toUser"];
        $permissionIds = $requestBody["permissions"];

        (new RemoveItemPermission($this->service))->handleRequest([
            RemoveItemPermission::ITEM_ID => $itemId,
            RemoveItemPermission::USER_ID => $userId,
            RemoveItemPermission::PERMISSIONS => $permissionIds
        ]);

        $data = [
            "status" => "success",
            "itemId" => $itemId,
            "removedPermissions" => $permissionIds
        ];

        return $response->withJson($data, 201);
    }

    /**
     * @return string[]
     */
    public static function getPermissionCodes()
    {
        return [
            Permission::READ,
            Permission::REMOVE_GRANT
        ];
    }
}