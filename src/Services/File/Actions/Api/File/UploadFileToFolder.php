<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 12:43
 */

namespace MedevOffice\Services\File\Actions\Api\File;


use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\File\SaveFile;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use MedevSlim\Core\Service\Exceptions\BadRequestException;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class UploadFileToFolder extends APIServlet implements PermissionRestricted
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
        $folderId = $args[OfficeFileService::FOLDER_ID];
        /** @var UploadedFileInterface $uploadedItem */
        $uploadedItem = $request->getUploadedFiles()["fileItem"];
        $requestBody = $request->getParsedBody();


        if (!$uploadedItem || $uploadedItem->getError() !== UPLOAD_ERR_OK) {
            throw new BadRequestException("File can not be uploaded. ErrorCode: " . ($uploadedItem ? $uploadedItem->getError() : "Unknown"));
        }

        $saveFile = new SaveFile($this->service);

        $file = $saveFile->handleRequest([
            SaveFile::HTTP_FILE => $uploadedItem,
            SaveFile::PARENT_FOLDER => $folderId,
            SaveFile::AUTHOR => $authToken->getUser()->getIdentifier(),
            SaveFile::INHERIT_PERMISSIONS => filter_var($requestBody["inheritPermissions"] ?? false, FILTER_VALIDATE_BOOLEAN)
        ]);

        $data = [
            "state" => "success",
            "containerFolder" => $folderId,
            "itemId" => $file->getIdentifier()
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
            Permission::CREATE
        ];
    }
}