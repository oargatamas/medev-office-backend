<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 14:16
 */

namespace MedevOffice\Services\File\Actions\Repository\Permission;


use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\Persistables\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Database\Medoo\MedooDatabase;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class AddItemPermission extends APIRepositoryAction
{
    const ITEM_ID = "itemId";
    const PERMISSIONS = "permissions";

    /**
     * @param $args
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::ITEM_ID];
        /** @var Entities\Permission[] $permissionsToAdd */
        $permissionsToAdd = $args[self::PERMISSIONS];

        $insertData = [];
        foreach ($permissionsToAdd as $permission){
            $insertData[] = [
                "ItemId" => $itemId,
                "PermissionId" => $permission->getIdentifier(),
                "UserId" => $permission->getUserId(),
                "Approval" => $permission->getApproval(),
                "CreatedAt" => $permission->getCreatedAt()->format(MedooDatabase::DEFAULT_DATE_FORMAT)
            ];
        }

        $this->database->insert(Permission::getTableName(),$insertData);

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Permissions can not be added to item. ".implode(" - ",$result));
        }
    }
}