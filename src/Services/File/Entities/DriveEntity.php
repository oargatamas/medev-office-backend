<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 9:58
 */

namespace MedevOffice\Services\File\Entities;


use DateTime;
use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;

abstract class DriveEntity extends DatabaseEntity implements \JsonSerializable
{
    const PERMISSION_ALL = -1;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $author;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getAuthor(): int
    {
        return $this->author;
    }

    /**
     * @param int $author
     */
    public function setAuthor(int $author)
    {
        $this->author = $author;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
}