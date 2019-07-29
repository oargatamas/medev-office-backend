<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 11:40
 */

namespace MedevOffice\Services\File\Entities;


use DateTime;
use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;

class Permission extends DatabaseEntity implements \JsonSerializable
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var int
     */
    private $userId;

    /**
     * @var int
     */
    private $approval;


    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getApproval()
    {
        return $this->approval;
    }

    /**
     * @param int $approval
     */
    public function setApproval($approval)
    {
        $this->approval = $approval;
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
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->getCode(),
            "createdAt" => $this->getCreatedAt()
        ];
    }
}