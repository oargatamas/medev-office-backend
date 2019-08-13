<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 14:00
 */

namespace MedevOffice\Services\File\Actions\Api\File;


use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\File\GetFileMeta;
use MedevOffice\Services\File\Actions\Repository\File\UpdateFileMeta;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class EditFile extends APIServlet implements PermissionRestricted
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
        $fileId = $args[OfficeFileService::FILE_ID];
        $requestBody = $request->getParsedBody();

        $getFileInfo = new GetFileMeta($this->service);
        $fileInfo = $getFileInfo->handleRequest([
            GetFileMeta::FILE_ID => $fileId,
            GetFileMeta::REQUESTER => $authToken->getUser()->getIdentifier()
        ]);

        //Todo add some kind of field validation logic here. e.g. empty string
        $fileInfo->setFilename($requestBody["fileName"]);
        $fileInfo->setMimetype($requestBody["mimeType"]);



        (new UpdateFileMeta($this->service))->handleRequest([
            UpdateFileMeta::FILE => $fileInfo
        ]);


        $data = [
            "status" => "success",
            "updated" => true
        ];

        return $response->withJson($data,200);
    }

    /**
     * @return string[]
     */
    public static function getPermissionCodes()
    {
        return [
            Permission::READ,
            Permission::UPDATE
        ];
    }
}