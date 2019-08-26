<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 13:40
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;


use DateTime;
use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\Folder\AddFolder;
use MedevOffice\Services\File\Entities\Folder;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use MedevSlim\Utils\UUID\UUID;
use Slim\Http\Request;
use Slim\Http\Response;

class CreateFolder extends APIServlet implements PermissionRestricted
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

        $parentFolder = $args[OfficeFileService::FOLDER_ID];
        $requestBody = $request->getParsedBody();
        $author = $authToken->getUser();
        $now = new DateTime();

        $folder = new Folder();
        $folder->setIdentifier(UUID::v4());
        $folder->setFoldername($requestBody["folderName"]);
        $folder->setAuthor($author->getIdentifier());
        $folder->setCreatedAt($now);
        $folder->setUpdatedAt($now);

        (new AddFolder($this->service))->handleRequest([
            AddFolder::FOLDER => $folder,
            AddFolder::PARENT_ID => $parentFolder
        ]);


        $data = [
            "status" => "success",
            "folderId" => $folder->getIdentifier()
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
            Permission::CREATE
        ];
    }
}