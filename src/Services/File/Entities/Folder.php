<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 10:52
 */

namespace MedevOffice\Services\File\Entities;


use DateTime;
use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;

class Folder extends DatabaseEntity
{
    /**
     * @var string
     */
    private $folderName;

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
     * @return string
     */
    public function getFoldername()
    {
        return $this->folderName;
    }

    /**
     * @param string $folderName
     */
    public function setFoldername($folderName)
    {
        $this->folderName = $folderName;
    }

    /**
     * @return int
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param int $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

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