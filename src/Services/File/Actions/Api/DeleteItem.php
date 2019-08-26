<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 13:24
 */

namespace MedevOffice\Services\File\Actions\Api;


use MedevOffice\Services\File\Actions\Repository\UnlinkItem;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class DeleteItem extends APIServlet implements PermissionRestricted
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

        (new UnlinkItem($this->service))->handleRequest([
            UnlinkItem::ITEM_ID => $itemId
        ]);

        $data = [
            "status" => "success",
            "deleted" => "true"
        ];

        return $response->withJson($data,201);
    }


    /**
     * @return string[]
     */
    public static function getPermissionCodes()
    {
        return [
            Permission::READ,
            Permission::DELETE
        ];
    }
}