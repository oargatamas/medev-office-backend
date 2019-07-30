<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 10:42
 */

namespace MedevOffice\Services\File\Actions\Repository\Permission;


use MedevOffice\Services\File\Entities;
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
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        /** @var string[] $requiredPermissions */
        $requiredPermissions = $args[self::PERMISSIONS];

        $getPermissions = new GetItemPermissions($this->service);

        $userPermissions = $getPermissions->handleRequest([
           GetItemPermissions::USER_ID =>  $args[self::USER_ID],
           GetItemPermissions::ITEM_ID =>  $args[self::ITEM_ID],
        ]);

        $userPermissions = array_map(function(Entities\Permission $permission){
            return $permission->getIdentifier();
        },$userPermissions);

        $commonItems = array_intersect($requiredPermissions,$userPermissions);
        if( $commonItems != $requiredPermissions){
            throw new UnauthorizedException("User has not enough permission to execute command. Required permission : [".implode($requiredPermissions,',')."], User permission: [". implode($userPermissions,',')."]");
        }
    }
}