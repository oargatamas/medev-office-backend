<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 14:45
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;


use MedevOffice\Services\File\Actions\Repository\GetChildFiles;
use MedevOffice\Services\File\Actions\Repository\GetChildFolders;
use MedevOffice\Services\File\Entities\Folder;
use MedevOffice\Services\File\FileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class GetFolderContent extends APIServlet
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
        $folderId = $args[FileService::FOLDER_ID];

        $getFolders = new GetChildFolders($this->service);
        $folders = $getFolders->handleRequest([Folder::ID => $folderId, "userId" => 1]);

        $getFiles = new GetChildFiles($this->service);
        $files = $getFiles->handleRequest([Folder::ID => $folderId, "userId" => 1]);

        return $response->withJson(array_merge($folders,$files));
    }
}