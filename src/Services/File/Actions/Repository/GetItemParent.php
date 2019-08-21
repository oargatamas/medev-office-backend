<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 15.
 * Time: 15:17
 */

namespace MedevOffice\Services\File\Actions\Repository;


use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderMeta;
use MedevOffice\Services\File\Entities\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class GetItemParent extends APIRepositoryAction
{
    const ITEM_ID = "itemId";

    /**
     * @param $args
     * @return Folder|null
     * @throws InternalServerException
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::ITEM_ID];

        $storedData = $this->database->get("Archive_ItemHierarchy(ih)",
            ["ih.ParentId"],
            ["ih.ItemId" => $itemId]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Can not retrieve parent of file: ".implode(" - ",$result));
        }

        if(!$storedData){
            return null;
        }

        $getFolder = new GetFolderMeta($this->service);
        $folder = $getFolder->handleRequest([
            GetFolderMeta::FOLDER_ID => $storedData["ParentId"]
        ]);

        return $folder;
    }
}