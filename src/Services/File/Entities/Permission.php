<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 11:40
 */

namespace MedevOffice\Services\File\Entities;


use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;

class Permission extends DatabaseEntity
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
}