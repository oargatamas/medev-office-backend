<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 01.
 * Time: 16:10
 */

namespace MedevOffice\Services\Core\Actions\Api;


use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class FetchUserModules extends APIServlet
{

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function handleRequest(Request $request, Response $response, $args)
    {

        $data = [
            [
                "name" => "dashboard",
                "enabled" => true
            ],
            [
                "name" => "drive",
                "enabled" => true
            ]
        ];

        return $response
            ->withJson($data, 200);
    }
}