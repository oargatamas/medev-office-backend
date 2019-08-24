<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 12:52
 */

namespace MedevOffice\Services\File\Actions\Api\Permission;


use DateTime;
use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\Permission\UpdateItemPermissions;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use MedevSlim\Core\Service\Exceptions\BadRequestException;
use Slim\Http\Request;
use Slim\Http\Response;

class GrantPermission extends APIServlet implements PermissionRestricted
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
        $itemId = $args[OfficeFileService::FILE_ID];
        $requestBody = $request->getParsedBody();
        $now = new DateTime();

        $permissions = [];

        try {
            foreach ($requestBody["permissions"] as $userId => $permissionIds) {
                foreach ($permissionIds as $permissionId) {
                    $item = new Permission();

                    $item->setIdentifier($permissionId);
                    $item->setApproval($authToken->getUser()->getIdentifier());
                    $item->setUserId($userId);
                    $item->setCreatedAt($now);

                    $permissions[] = $item;
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            throw new BadRequestException("Error in request body. Permissions can not be parsed: ". $e->getMessage());
        }

        (new UpdateItemPermissions($this->service))->handleRequest([
            UpdateItemPermissions::ITEM_ID => $itemId,
            UpdateItemPermissions::PERMISSIONS => $permissions
        ]);


        $data = [
            "status" => "success",
            "itemId" => $itemId,
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
            Permission::GRANT_PERMISSION
        ];
    }
}