<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 12:04
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class MoveItemToFolder extends APIRepositoryAction
{
    const ITEM_ID = "itemId";
    const FOLDER_ID = "folderId";


    /**
     * @param $args
     * @throws InternalServerException
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::ITEM_ID];
        $folderId = $args[self::FOLDER_ID];

        $this->database->replace("Archive_ItemHierarchy",
            [
                "ParentId" => $folderId
            ],
            [
                "ItemId" => $itemId
            ]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Item can not be moved to folder: ".implode(" - ",$result));
        }
    }
}