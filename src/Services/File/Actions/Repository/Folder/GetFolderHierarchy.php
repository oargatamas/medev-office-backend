<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 29.
 * Time: 13:50
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;

use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Entities\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class GetFolderHierarchy extends APIRepositoryAction
{
    const ROOT_FOLDER = "rootFolder";
    const INCLUDE_FILES = "includeFiles";

    /**
     * @param $args
     * @return Folder
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        /** @var OAuthToken $authToken */
        $authToken = $args[OAuthService::AUTH_TOKEN];
        /** @var Folder $rootFolder */
        $rootFolder = $args[self::ROOT_FOLDER];

        $includeFiles = $args[self::INCLUDE_FILES] ?? false;

        $getFolders = new GetFolderItems($this->service);
        $rootFolderItems = $getFolders->handleRequest([
            OAuthService::AUTH_TOKEN => $authToken,
            GetFolderItems::FOLDER_ID => $rootFolder->getIdentifier(),
            GetFolderItems::EXCLUDE_FILES => !$includeFiles,
        ]);

        $rootFolder->setContent($rootFolderItems);

        if(count($rootFolder->getContent()) > 0) {
            foreach ($rootFolder->getContent() as $driveItem) {
                if($driveItem instanceof Folder){
                    (new GetFolderHierarchy($this->service))->handleRequest([
                        OAuthService::AUTH_TOKEN => $authToken,
                        GetFolderHierarchy::ROOT_FOLDER => $driveItem,
                        GetFolderHierarchy::INCLUDE_FILES => $includeFiles,
                    ]);
                }
            }
        }

        return $rootFolder;
    }
}