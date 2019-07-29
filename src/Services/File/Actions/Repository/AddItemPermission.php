<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 14:16
 */

namespace MedevOffice\Services\File\Actions\Repository;


use DateTime;
use MedevOffice\Services\File\Entities\Persistables\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Database\Medoo\MedooDatabase;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class AddItemPermission extends APIRepositoryAction
{
    const ITEM_ID = "itemId";
    const USER_ID = "userId";
    const APPROVAL_ID = "approvalId";
    const PERMISSIONS = "permissions";

    /**
     * @param $args
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $itemId = $args[self::ITEM_ID];
        $userId = $args[self::USER_ID];
        $approval = $args[self::APPROVAL_ID];
        $permissionsToAdd = $args[self::PERMISSIONS];

        $insertData = [];
        foreach ($permissionsToAdd as $permission){
            $insertData[] = [
                "ItemId" => $itemId,
                "PermissionId" => $permission,
                "UserId" => $userId,
                "Approval" => $approval,
                "CreatedAt" => (new DateTime())->format(MedooDatabase::DEFAULT_DATE_FORMAT)
            ];
        }

        $this->database->insert(Permission::getTableName(),$insertData);

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Permissions can not be added to item. ".implode(" - ",$result));
        }
    }
}