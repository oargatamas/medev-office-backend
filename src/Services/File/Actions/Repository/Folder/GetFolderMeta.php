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
use MedevSlim\Core\Service\Exceptions\InternalServerException;

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
        $itemId = $args[self::FOLDER_ID];
        $requester = $args[self::REQUESTER];

        $storedData = $this->database->get(
            Folder::getTableName() . "(folder)",
            Folder::getColumnNames(),
            ["folder.Id" => $itemId]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Can not retrieve folder meta: ".implode(" - ",$result));
        }

        $folder = Folder::fromAssocArray($storedData);

        $getPermissions = new GetItemPermissions($this->service);
        $folder->setPermissions($getPermissions->handleRequest([
            GetItemPermissions::ITEM_ID => $folder->getIdentifier(),
            GetItemPermissions::USER_ID => $requester
        ]));


        return $folder;
    }
}