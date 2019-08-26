<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 14:14
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;


use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderMeta;
use MedevOffice\Services\File\Actions\Repository\Folder\UpdateFolderMeta;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class EditFolder extends APIServlet implements PermissionRestricted
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
        $folderId = $args[OfficeFileService::FOLDER_ID];
        $requestBody = $request->getParsedBody();

        $getFileInfo = new GetFolderMeta($this->service);
        $folderInfo = $getFileInfo->handleRequest([
            GetFolderMeta::FOLDER_ID => $folderId
        ]);

        //Todo add some kind of field validation logic here. e.g. empty string
        $folderInfo->setFoldername($requestBody["folderName"]);



        (new UpdateFolderMeta($this->service))->handleRequest([
            UpdateFolderMeta::FOLDER => $folderInfo
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