<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 14.
 * Time: 15:42
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;
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

        $itemIds = array_map(function(DatabaseEntity $entity){
            return $entity->getIdentifier();
        },$items);

        $getPermissions = new GetItemPermissions($this->service);
        $permissions = $getPermissions->handleRequest([
            GetItemPermissions::ITEM_ID => $itemIds,
            GetItemPermissions::USER_ID => $userId
        ]);

        foreach ($items as $item){
            $item->setPermissions($permissions[$item->getIdentifier()]);
        }

        $permissionsCheck =  new ValidatePermission($this->service);

        $filteredItems = array_filter($items,function(DriveEntity $item) use($permissionsCheck){
            return $permissionsCheck->handleRequest([
                ValidatePermission::ITEM_PERMISSIONS => $item->getPermissions(),
                ValidatePermission::PERMISSIONS => [Permission::READ]
            ]);
        });

        return $filteredItems;
    }
}