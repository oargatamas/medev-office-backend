<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 12:29
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevOffice\Services\File\Actions\Repository\Permission\GetItemPermissions;
use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\Persistables\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class GetFolderMeta extends APIRepositoryAction
{
    const REQUESTER = "requester";
    const FOLDER_ID = "folderId";

    /**
     * @param $args
     * @return Entities\Folder
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::FOLDER_IDs];
        $requester = $args[self::REQUESTER];

        $storedData = $this->database->get(
            Folder::getTableName() . "(file)",
            Folder::getColumnNames(),
            ["file.Id" => $itemId]
        );

        $folder = Folder::fromAssocArray($storedData);

        $getPermissions = new GetItemPermissions($this->service);
        $folder->setPermissions($getPermissions->handleRequest([
            GetItemPermissions::ITEM_ID => $folder->getIdentifier(),
            GetItemPermissions::USER_ID => $requester
        ]));


        return $folder;
    }
}