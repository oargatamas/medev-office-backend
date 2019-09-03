<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 13.
 * Time: 13:05
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;


use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderItems;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderMeta;
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
        /** @var OAuthToken $authToken */
        $authToken = $request->getAttribute(OAuthService::AUTH_TOKEN);
        $withMeta = filter_var($request->getParam("meta",false),FILTER_VALIDATE_BOOLEAN);
        $withContent = filter_var($request->getParam("content",false),FILTER_VALIDATE_BOOLEAN);

        $getRootFolderId = new GetRootFolderId($this->service);
        $rootFolderId = $getRootFolderId->handleRequest();

        $data = [
            "id" => $rootFolderId
        ];


        if($withMeta){
            $getFolderMeta = new GetFolderMeta($this->service);
            $folderInfo = $getFolderMeta->handleRequest([
                GetFolderMeta::FOLDER_ID => $rootFolderId
            ]);

            $data["meta"] = $folderInfo;
        }

        if($withContent){
            $getFolderContent = new GetFolderItems($this->service);
            $items = $getFolderContent->handleRequest([
                OAuthService::AUTH_TOKEN => $authToken,
                GetFolderItems::FOLDER_ID => $rootFolderId
            ]);
            $data["content"] = $items;
        }

        return $response->withJson($data,200);
    }

}