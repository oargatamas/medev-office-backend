<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 13:09
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class AssignItemToFolder extends APIRepositoryAction
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

        $this->database->insert("Archive_ItemHierarchy",
            [
                "ItemId" => $itemId,
                "ParentId" => $folderId,
            ]);


        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Item can not be assigned to folder: ".implode(" - ",$result));
        }
    }
}