<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 22.
 * Time: 8:19
 */

namespace MedevOffice\Services\Core\Actions\Api;


use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class FetchUserInfo extends APIServlet
{

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function handleRequest(Request $request, Response $response, $args)
    {
        $authHost = $this->config["authorization"]["host"];

        $url = "https://".$authHost."/user/info";

        $this->info("Office API not storing user related information. Redirecting client to ". $url. ".");

        return $response->withRedirect($url,302);
    }
}