<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 30.
 * Time: 15:12
 */

namespace MedevOffice\Services\File\Actions\Repository\Permission;

use MedevOffice\Services\File\Entities\Persistables\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class GetItemPermissions extends APIRepositoryAction
{
    const ITEM_ID = "itemId";
    const USER_ID = "userId";
    const ALL_USER = "allUser";

    /**
     * @param $args
     * @return array(Entities\Permission[])
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $itemIds = is_array($args[self::ITEM_ID]) ? $args[self::ITEM_ID] : [$args[self::ITEM_ID]];
        $userId = $args[self::USER_ID] ?? self::ALL_USER;

        $storedData = $this->database->select(Permission::getTableName()."(p)",
            Permission::getColumnNames(),
            $this->userFilter($userId,$itemIds)
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Permissions can not be queried. ".implode(" - ",$result));
        }

        $permissionsOfUser = [];

        foreach ($storedData as $record){
            $permissionsOfUser[$record["ItemId"]][] = Permission::fromAssocArray($record);
        }

        foreach ($itemIds as $itemId){
            if(!array_key_exists($itemId,$permissionsOfUser)){
                $permissionsOfUser[$itemId] = [];
            }
        }


        if(count($permissionsOfUser) === 1){
            $firstItem = reset($permissionsOfUser);
            return $firstItem;
        }
        return $permissionsOfUser;
    }

    private function userFilter($user,$itemIds){
        if($user !== self::ALL_USER){
            return [
                "AND" => [
                    "p.ItemId" => $itemIds,
                    "p.UserId" => $user
                ]
            ];
        }else{
            return [
                "p.ItemId" => $itemIds,
            ];
        }
    }
}