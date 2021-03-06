<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 14:09
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\Persistables\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class GetChildFolders extends APIRepositoryAction
{

    /**
     * @param $args
     * @return Entities\Folder[]
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $folderId = $args[Entities\Folder::ID];

        $storedData = $this->database->select(Folder::getTableName() . "(folder)",
            ["[>]Archive_ItemHierarchy(h)" => ["folder.Id" => "ItemId"]],
            Folder::getColumnNames(),
            ["h.ParentId" => $folderId,]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Can not retrieve folders of folder: ".implode(" - ",$result));
        }

        $childItems = [];

        foreach ($storedData as $record) {
            $childItems[] = Folder::fromAssocArray($record);
        }

        return $childItems;
    }
}