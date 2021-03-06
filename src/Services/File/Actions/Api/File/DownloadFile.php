<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 15:22
 */

namespace MedevOffice\Services\File\Actions\Api\File;


use MedevOffice\Services\File\Actions\Repository\File\GetFileMeta;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use MedevSlim\Core\Service\Exceptions\BadRequestException;
use Slim\Http\Request;
use Slim\Http\Response;

class DownloadFile extends APIServlet implements PermissionRestricted
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
        $fileId = $args[OfficeFileService::FILE_ID];

        $getFileInfo = new GetFileMeta($this->service);
        $fileInfo = $getFileInfo->handleRequest([GetFileMeta::FILE_ID => $fileId]);

        $path = $fileInfo->getFullPath();

        if (!file_exists($path)) {
            throw new BadRequestException("File not found at '" . $path . "' .");
        }

        $fh = fopen($path, 'rb');
        $stream = new \Slim\Http\Stream($fh);

        return $response
            ->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $fileInfo->getName() . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }


    /**
     * @return string[]
     */
    public static function getPermissionCodes()
    {
        return [
            Permission::READ,
            Permission::DOWNLOAD,
        ];
    }
}