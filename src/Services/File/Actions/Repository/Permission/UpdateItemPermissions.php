<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 08. 24.
 * Time: 11:04
 */

namespace MedevOffice\Services\File\Actions\Repository\Permission;


use MedevOffice\Services\File\Entities\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class UpdateItemPermissions extends APIRepositoryAction
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
        /** @var Permission[] $permissions */
        $permissions = $args[self::PERMISSIONS];

        $this->database->action(function () use ($itemId, $permissions) {
            $userIds = array_map(function (Permission $permission) {
                return $permission->getUserId();
            }, $permissions);

            (new ClearItemPermission($this->service))->handleRequest([
                ClearItemPermission::ITEM_ID => $itemId,
                ClearItemPermission::USER_ID => $userIds
            ]);

            (new AddItemPermission($this->service))->handleRequest([
                AddItemPermission::ITEM_ID => $itemId,
                AddItemPermission::PERMISSIONS => $permissions
            ]);
        });
    }
}