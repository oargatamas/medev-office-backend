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
use Medoo\Medoo;

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
        $userId = $args["userId"];

        //Todo integrate it with the GetItemPermissions action and make the filtering here in PHP
        $storedData = $this->database->select(Folder::getTableName()."(folder)",
            [
                "[>]Archive_ItemHierarchy(h)" => ["folder.Id" => "ItemId"],
                "[>]Archive_ItemPermissions(ip)" => ["folder.Id" => "ItemId"]
            ],
            array_merge(
                Folder::getColumnNames(),
                ["Permissions" => Medoo::raw("GROUP_CONCAT(DISTINCT(<ip.PermissionId>))")]
            ),
            [
                "AND" => [
                  "h.ParentId" => $folderId,
                  "ip.UserId" => $userId
                ],
                "GROUP" => "folder.Id"
            ]
        );

        $childItems = [];

        foreach ($storedData as $record){
            $childItems[] = Folder::fromAssocArray($record);
         }

        return $childItems;
    }
}