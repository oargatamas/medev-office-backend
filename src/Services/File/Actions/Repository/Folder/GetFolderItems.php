<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 14.
 * Time: 15:42
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;
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
    const USER_ID = "userId";

    /**
     * @param $args
     * @return DriveEntity[]
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $folderId = $args[self::FOLDER_ID];
        $userId = $args[self::USER_ID];

        $getFolders = new GetChildFolders($this->service);
        $folders = $getFolders->handleRequest([Folder::ID => $folderId]);

        $getFiles = new GetChildFiles($this->service);
        $files = $getFiles->handleRequest([Folder::ID => $folderId]);

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

            $filteredItems = array_filter($items,function(DriveEntity $item) use($permissionsCheck, $userId, $args){
                return $permissionsCheck->handleRequest([
                    OAuthService::AUTH_TOKEN => $args[OAuthService::AUTH_TOKEN],
                    ValidatePermission::ITEM_PERMISSIONS => $item->getPermissions($userId),
                    ValidatePermission::PERMISSIONS => [Permission::READ]
                ]);
            });

            return array_values($filteredItems);
        }

        return $items;
    }
}