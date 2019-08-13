<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 13.
 * Time: 13:05
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;


use MedevOffice\Services\File\Actions\Repository\Folder\GetRootFolderId;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class GetRootFolder extends APIServlet
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
        $getRootFolderId = new GetRootFolderId($this->service);
        $rootFolderId = $getRootFolderId->handleRequest();

        $data = [
            "id" => $rootFolderId
        ];

        return $response->withJson($data,200);
    }

}