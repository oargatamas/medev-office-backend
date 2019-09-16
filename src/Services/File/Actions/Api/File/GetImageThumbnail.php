<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 09. 16.
 * Time: 13:58
 */

namespace MedevOffice\Services\File\Actions\Api\File;


use Gumlet\ImageResize;
use MedevOffice\Services\File\Actions\Repository\File\GetFileMeta;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use MedevSlim\Core\Service\Exceptions\BadRequestException;
use Slim\Http\Request;
use Slim\Http\Response;

class GetImageThumbnail extends APIServlet implements PermissionRestricted
{
    const IMAGE_SIZE_SMALL = "small";
    const IMAGE_SIZE_MEDIUM = "medium";
    const IMAGE_SIZE_LARGE = "large";
    const IMAGE_SIZE_ORIG = "original";
    const IMAGE_SIZES = [
        self::IMAGE_SIZE_SMALL => [100,100],
        self::IMAGE_SIZE_MEDIUM => [300,300],
        self::IMAGE_SIZE_LARGE => [500,500],
    ];
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     * @throws BadRequestException
     * @throws \Exception
     */
    public function handleRequest(Request $request, Response $response, $args)
    {
        $fileId = $args[OfficeFileService::FILE_ID];
        $desiredSize = $request->getParam("size",self::IMAGE_SIZE_ORIG);

        $getFileInfo = new GetFileMeta($this->service);
        $fileInfo = $getFileInfo->handleRequest([GetFileMeta::FILE_ID => $fileId]);

        $basePath = $_SERVER["DOCUMENT_ROOT"] . $this->config["application"]["drive"]["documentPath"];
        $path = $basePath . "/" . $fileInfo->getPath() . $fileInfo->getIdentifier();

        if(strpos($fileInfo->getMimetype(), 'image/') !== 0){
           throw new BadRequestException("File is not an image based on the mime type: ".$fileInfo->getMimetype());
        }

        if (!file_exists($path)) {
            throw new BadRequestException("File not found at '" . $path . "' .");
        }

        $image = new ImageResize($path);
        if($desiredSize !== self::IMAGE_SIZE_ORIG){
            $size = self::IMAGE_SIZES[$desiredSize];
            $image->resizeToWidth($size[0]);
        }
        $response->getBody()->write($image->getImageAsString());

        return $response
            ->withHeader("Content-Type", $fileInfo->getMimetype());
    }

    /**
     * @return string[]
     */
    public static function getPermissionCodes()
    {
        return [Permission::READ];
    }
}