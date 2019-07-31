<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 9:58
 */

namespace MedevOffice\Services\File\Entities;


use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;

abstract class DriveEntity extends DatabaseEntity
{
    /**
     * @var Permission[]
     */
    private $permissions;

    /**
     * @return Permission[]
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param Permission[] $permissions
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }
}