<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 11:45
 */

namespace MedevOffice\Services\File\Entities\Persistables;


use MedevAuth\Services\Auth\OAuth\Entity\DatabaseEntity;
use MedevAuth\Services\Auth\OAuth\Entity\Persistables\MedooPersistable;

class Permission implements MedooPersistable
{

    /**
     * @param $storedData
     * @return DatabaseEntity
     */
    public static function fromAssocArray($storedData)
    {
        // TODO: Implement fromAssocArray() method.
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return "Archive_ItemPermissions";
    }

    /**
     * @return string[]
     */
    public static function getColumnNames()
    {
        // TODO: Implement getColumnNames() method.
    }
}