<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 9:58
 */

namespace MedevOffice\Services\File\Entities;


use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;

abstract class DriveEntity extends DatabaseEntity implements \JsonSerializable
{
    const PERMISSION_ALL = -1;
    /**
     * @var Permission[]
     */
    private $permissions;

    /**
     * @param int $userId
     * @return Permission[]
     */
    public function getPermissions($userId = self::PERMISSION_ALL)
    {
        if($userId !== self::PERMISSION_ALL){
            $filtered = array_filter($this->permissions,function(Permission $item) use ($userId){
                return $item->getUserId() === $userId;
            });
            return array_values($filtered);
        }
        return $this->permissions;
    }

    public function getPermissionsByUser(){
        $result = [];
        foreach ($this->permissions as $permission){
            $result[$permission->getUserId()][] = $permission;
        }
        return $result;
    }

    /**
     * @param Permission[] $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }
}