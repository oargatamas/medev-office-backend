<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 13:32
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevOffice\Services\File\Actions\Repository\Permission\AddItemPermission;
use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\Persistables\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class AddFolder extends APIRepositoryAction
{
    const FOLDER = "folder";
    const PARENT_ID = "parentFolder";
    const INHERIT_PERMISSIONS = "inheritPermissions";

    /**
     * @param $args
     * @throws InternalServerException
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        /** @var \MedevOffice\Services\File\Entities\Folder $folder */
        $folder = $args[self::FOLDER];
        $inheritPermissions = $args[self::INHERIT_PERMISSIONS];


        $this->database->action(function () use ($folder, $args, $inheritPermissions) {

            $parentFolder = (new GetFolderMeta($this->service))->handleRequest([
                GetFolderMeta::FOLDER_ID => $args[self::PARENT_ID]
            ]);

            $itemPermissions = $parentFolder->getPermissions();
            if (!$inheritPermissions) {
                $itemPermissions = Permission::createPermissions($folder->getAuthor(), $folder->getAuthor(), Entities\Permission::AUTHOR);
            }

            (new PersistFolderMeta($this->service))->handleRequest([
                PersistFolderMeta::FOLDER => $folder
            ]);

            (new AddItemPermission($this->service))->handleRequest([
                AddItemPermission::ITEM_ID => $folder->getIdentifier(),
                AddItemPermission::PERMISSIONS => $itemPermissions
            ]);

            (new AssignItemToFolder($this->service))->handleRequest([
                AssignItemToFolder::ITEM_ID => $folder->getIdentifier(),
                AssignItemToFolder::FOLDER_ID => $parentFolder->getIdentifier()
            ]);
        });

        $result = $this->database->error();
        if (!is_null($result[2])) {
            throw new InternalServerException("Can not insert folder data: " . implode(" - ", $result));
        }
    }
}