<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 10:42
 */

namespace MedevOffice\Services\File\Actions\Repository;


use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\Persistables\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\UnauthorizedException;

class ValidatePermission extends APIRepositoryAction
{
    const PERMISSIONS = "permissions";
    const ITEM_ID = "itemId";
    const USER_ID = "userId";

    /**
     * @param $args
     * @throws UnauthorizedException
     */
    public function handleRequest($args = [])
    {
        /** @var Entities\Permission[] $requiredPermissions */
        $requiredPermissions = $args[self::PERMISSIONS];
        $itemId = $args[self::ITEM_ID];
        $userId = $args[self::USER_ID];

        $storedData = $this->database->select(Permission::getTableName()."(p)",
            Permission::getColumnNames(),
            [
                "AND" => [
                    "p.ItemId" => $itemId,
                    "p.UserId" => $userId
                ]
            ]
        );

        $userPermissions = [];
        foreach ($storedData as $record){
            $userPermissions[] = Permission::fromAssocArray($record);
        }

        $commonItems = array_intersect($requiredPermissions,$userPermissions);
        if( $commonItems != $requiredPermissions){
            throw new UnauthorizedException("User has not enough permission to execute command. Required permission : [".implode($requiredPermissions,',')."], User permission: [". implode($userPermissions,',')."]");
        }
    }
}