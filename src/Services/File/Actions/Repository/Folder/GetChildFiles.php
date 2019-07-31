<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 15:51
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\Folder;
use MedevOffice\Services\File\Entities\Persistables\File;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class GetChildFiles extends APIRepositoryAction
{

    /**
     * @param $args
     * @return Entities\File[]
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $folderId = $args[Folder::ID];

        $storedData = $this->database->select(File::getTableName()."(file)",
            ["[>]Archive_ItemHierarchy(h)" => ["file.Id" => "ItemId"]],
            File::getColumnNames(),
            ["h.ParentId" => $folderId]
        );

        $childItems = [];

        foreach ($storedData as $record){
            $childItems[] = File::fromAssocArray($record);
        }

        return $childItems;
    }
}