<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 12. 17.
 * Time: 14:02
 */

namespace MedevOffice\Services\File\Actions\Api\Folder;


use Genkgo\ArchiveStream\Psr7Stream;
use Genkgo\ArchiveStream\ZipReader;
use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\CompressItems;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderHierarchy;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderMeta;
use MedevOffice\Services\File\Entities\Permission;
use MedevOffice\Services\File\Middleware\PermissionRestricted;
use MedevOffice\Services\File\OfficeFileService;
use MedevSlim\Core\Action\Servlet\APIServlet;
use Slim\Http\Request;
use Slim\Http\Response;

class DownloadFolder extends APIServlet implements PermissionRestricted
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


        $folder = (new GetFolderMeta($this->service))->handleRequest([
            GetFolderMeta::FOLDER_ID => $folderId,
        ]);

        $folder = (new GetFolderHierarchy($this->service))->handleRequest([
            GetFolderHierarchy::ROOT_FOLDER => $folder,
            GetFolderHierarchy::INCLUDE_FILES => true,
            OAuthService::AUTH_TOKEN => $authToken,
        ]);

        $compress = new CompressItems($this->service);
        $compressedArchive = $compress->handleRequest([
            CompressItems::ITEMS => [$folder]
        ]);

        return $response
            ->withHeader('Content-Type', 'application/force-download')
            ->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Type', 'application/download')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="' . $folder->getName() . '.zip"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withBody(new Psr7Stream(new ZipReader($compressedArchive)));
    }

    /**
     * @return string[]
     */
    public static function getPermissionCodes()
    {
        return [
            Permission::READ,
            Permission::DOWNLOAD
        ];
    }
}