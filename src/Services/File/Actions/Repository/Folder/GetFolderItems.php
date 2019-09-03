<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 14.
 * Time: 15:42
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;
use MedevAuth\Services\Auth\OAuth\Entity\Token\OAuthToken;
use MedevAuth\Services\Auth\OAuth\OAuthService;
use MedevOffice\Services\File\Actions\Repository\Permission\GetItemPermissions;
use MedevOffice\Services\File\Actions\Repository\Permission\ValidatePermission;
use MedevOffice\Services\File\Entities\DriveEntity;
use MedevOffice\Services\File\Entities\Folder;
use MedevOffice\Services\File\Entities\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class GetFolderItems extends APIRepositoryAction
{

    const FOLDER_ID = "folderId";
    const EXCLUDE_FOLDERS = "excludeFolders";
    const EXCLUDE_FILES = "excludeFiles";

    /**
     * @param $args
     * @return DriveEntity[]
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        /** @var OAuthToken $authToken */
        $authToken = $args[OAuthService::AUTH_TOKEN];
        $folderId = $args[self::FOLDER_ID];

        $excludeFolders = $args[self::EXCLUDE_FOLDERS] ?? false;
        $excludeFiles = $args[self::EXCLUDE_FILES] ?? false;

        $getFolders = new GetChildFolders($this->service);
        $folders = !$excludeFolders ? $getFolders->handleRequest([Folder::ID => $folderId]) : [];

        $getFiles = new GetChildFiles($this->service);
        $files = !$excludeFiles ? $getFiles->handleRequest([Folder::ID => $folderId]) : [];

        /** @var DriveEntity[] $items */
        $items = array_merge($folders,$files);

        if(count($items) > 0){
            $itemIds = array_map(function(DatabaseEntity $entity){
                return $entity->getIdentifier();
            },$items);

            $getPermissions = new GetItemPermissions($this->service);
            $permissions = $getPermissions->handleRequest([
                GetItemPermissions::ITEM_ID => $itemIds,
            ]);

            foreach ($items as $item){
                $item->setPermissions($permissions[$item->getIdentifier()] ?? $permissions);
            }

            $permissionsCheck =  new ValidatePermission($this->service);

            $filteredItems = array_filter($items,function(DriveEntity $item) use($permissionsCheck, $authToken, $args){
                return $permissionsCheck->handleRequest([
                    OAuthService::AUTH_TOKEN => $args[OAuthService::AUTH_TOKEN],
                    ValidatePermission::ITEM_PERMISSIONS => $item->getPermissions($authToken->getUser()->getIdentifier()),
                    ValidatePermission::PERMISSIONS => [Permission::READ]
                ]);
            });

            return array_values($filteredItems);
        }

        return $items;
    }
}