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
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class GetRootFolderId extends APIRepositoryAction
{
    /**
     * @param $args
     * @return string
     * @throws InternalServerException
     */
    public function handleRequest($args = [])
    {
        $storedData = $this->database->get(Folder::getTableName()."(f)",
            ["[>]Archive_ItemHierarchy(ih)" => ["f.Id" => "itemId"]],
            ["f.Id"],
            [
                "AND" => [
                    "ih.ParentId" => null
                ]
            ]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Folder data can not be retrieved: ".implode(" - ",$result));
        }

        $rootFolderId = $storedData["Id"];

        return $rootFolderId;
    }
}