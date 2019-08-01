<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 11:45
 */

namespace MedevOffice\Services\File\Entities\Persistables;


use DateTime;
use MedevAuth\Services\Auth\OAuth\Entity\Persistables\MedooPersistable;
use MedevOffice\Services\File\Entities;
use Medoo\Medoo;

class Permission implements MedooPersistable
{

    /**
     * @param $storedData
     * @return Entities\Permission
     * @throws \Exception
     */
    public static function fromAssocArray($storedData)
    {
        $permission = new Entities\Permission();

        $permission->setIdentifier($storedData["PermissionId"]);
        $permission->setUserId($storedData["UserId"]);
        $permission->setApproval($storedData["Approval"]);
        $permission->setCreatedAt(new DateTime($storedData["CreatedAt"]));

        return $permission;
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
        return [
            "ItemId" => Medoo::raw("<p.ItemId>"),
            "PermissionId" => Medoo::raw("<p.PermissionId>"),
            "UserId" => Medoo::raw("<p.UserId>"),
            "Approval" => Medoo::raw("<p.Approval>"),
            "CreatedAt" => Medoo::raw("<p.CreatedAt>"),
        ];
    }
}