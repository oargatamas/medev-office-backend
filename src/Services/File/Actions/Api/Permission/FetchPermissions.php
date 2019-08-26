<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 21.
 * Time: 14:18
 */

namespace MedevOffice\Services\File\Actions\Api\Permission;


use MedevOffice\Services\File\Entities\Permission;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class FetchPermissions extends APIServlet
{

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function handleRequest(Request $request, Response $response, $args)
    {
        $data = Permission::ALL;

        return $response->withJson($data,200);
    }
}