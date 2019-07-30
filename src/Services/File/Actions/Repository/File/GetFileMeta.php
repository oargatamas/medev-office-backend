<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 15:23
 */

namespace MedevOffice\Services\File\Actions\Repository\File;

use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\Persistables\File;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class GetFileMeta extends APIRepositoryAction
{
    const REQUESTER = "requester";
    const FILE_ID = "fileId";

    /**
     * @param $args
     * @return Entities\File
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::FILE_ID];
        $requester = $args[self::REQUESTER];

        $storedData = $this->database->get(
            File::getTableName() . "(file)",
            File::getColumnNames(),
            ["file.Id" => $itemId]
        );

        $file = File::fromAssocArray($storedData);

        $getPermissions = new GetItemPermissions($this->service);
        $file->setPermissions($getPermissions->handleRequest([
            GetItemPermissions::ITEM_ID => $file->getIdentifier(),
            GetItemPermissions::USER_ID => $requester
        ]));


        return $file;
    }
}