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
use Medoo\Medoo;

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
        $userId = $args["userId"];

        //Todo integrate it with the GetItemPermissions action and make the filtering here in PHP
        $storedData = $this->database->select(File::getTableName()."(file)",
            [
                "[>]Archive_ItemHierarchy(h)" => ["file.Id" => "ItemId"],
                "[>]Archive_ItemPermissions(ip)" => ["file.Id" => "ItemId"]
            ],
            array_merge(
                File::getColumnNames(),
                ["Permissions" => Medoo::raw("GROUP_CONCAT(DISTINCT(<ip.PermissionId>))")]
            ),
            [
                "AND" => [
                    "h.ParentId" => $folderId,
                    "ip.UserId" => $userId
                ],
                "GROUP" => "file.Id"
            ]
        );

        $childItems = [];

        foreach ($storedData as $record){
            $childItems[] = File::fromAssocArray($record);
        }

        return $childItems;
    }
}