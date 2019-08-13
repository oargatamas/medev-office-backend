<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 13.
 * Time: 12:49
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevOffice\Services\File\Entities\Persistables\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class GetRootFolderId extends APIRepositoryAction
{
    /**
     * @param $args
     * @return string
     */
    public function handleRequest($args = [])
    {
        $storedData = $this->database->get(Folder::getTableName()."(f)",
            ["[>]Archive_ItemHierarchy(ih)" => ["itemId" => "f.itemId"]],
            ["f.ItemId"],
            [
                "AND" => [
                    "ih.ParentId" => null
                ]
            ]
        );

        $rootFolderId = $storedData["ItemId"];

        return $rootFolderId;
    }
}