<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 29.
 * Time: 10:52
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;


use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderHierarchy;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderMeta;
use MedevOffice\Services\File\Actions\Repository\Folder\GetRootFolderId;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class GetFolderTree extends APIServlet
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

        $rootFolderId = (new GetRootFolderId($this->service))->handleRequest();
        $rootFolderInfo = (new GetFolderMeta($this->service))->handleRequest([
            GetFolderMeta::FOLDER_ID => $rootFolderId,
        ]);

        (new GetFolderHierarchy($this->service))->handleRequest([
            GetFolderHierarchy::ROOT_FOLDER => $rootFolderInfo,
            OAuthService::AUTH_TOKEN => $authToken,
        ]);

        return $response->withJson($rootFolderInfo, 200);
    }
}