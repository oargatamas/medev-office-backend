<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 13:32
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevOffice\Services\File\Actions\Repository\Permission\AddItemPermission;
use MedevOffice\Services\File\Entities\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class AddFolder extends APIRepositoryAction
{
    const FOLDER = "folder";
    const PARENT_ID = "parentId";

    /**
     * @param $args
     * @throws InternalServerException
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        /** @var \MedevOffice\Services\File\Entities\Folder $folder */
        $folder = $args[self::FOLDER];
        $parentFolder = $args[self::PARENT_ID];

        $this->database->action(function ($database) use ($folder, $parentFolder) {

            (new PersistFolderMeta($this->service))->handleRequest([
                PersistFolderMeta::FOLDER => $folder
            ]);

            (new AddItemPermission($this->service))->handleRequest([
                AddItemPermission::ITEM_ID => $folder->getIdentifier(),
                AddItemPermission::USER_ID => $folder->getAuthor(),
                AddItemPermission::PERMISSIONS => Permission::ALL
            ]);

            (new AssignItemToFolder($this->service))->handleRequest([
                AssignItemToFolder::ITEM_ID => $folder->getIdentifier(),
                AssignItemToFolder::FOLDER_ID => $parentFolder
            ]);
        });

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Can not insert folder data: ".implode(" - ",$result));
        }
    }
}