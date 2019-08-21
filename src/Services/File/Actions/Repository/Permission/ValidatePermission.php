<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 10:42
 */

namespace MedevOffice\Services\File\Actions\Repository\Permission;


use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;
use MedevOffice\Services\File\Entities\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\ForbiddenException;

class ValidatePermission extends APIRepositoryAction
{
    const PERMISSIONS = "requiredPermissions";
    const ITEM_PERMISSIONS = "itemPermissions";
    const THROW_ERROR = "throwError";

    /**
     * @param $args
     * @return bool
     * @throws ForbiddenException
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {

        /** @var Permission[] $userPermissions */
        $userPermissions = $args[self::ITEM_PERMISSIONS];
        /** @var string[] $requiredPermissions */
        $requiredPermissions = $args[self::PERMISSIONS];
        $throwError = $args[self::THROW_ERROR] ?? false;

        $userPermissions = array_map(function(DatabaseEntity $entity){
            return $entity->getIdentifier();
        },$userPermissions);

        $commonItems = array_intersect($requiredPermissions,$userPermissions);
        if( $commonItems != $requiredPermissions){
            $msg = "User has not enough permission to execute command. Required permission : [".implode($requiredPermissions,',')."], User permission: [". implode($userPermissions,',')."]";
            if($throwError){
                throw new ForbiddenException($msg);
            }else{
                $this->error($msg);
                return false;
            }
        }
        return true;
    }
}