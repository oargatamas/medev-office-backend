<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 10:53
 */

namespace MedevOffice\Services\File\Entities\Persistables;


use DateTime;
use MedevAuth\Services\Auth\OAuth\Entity\Persistables\MedooPersistable;
use MedevOffice\Services\File\Entities;
use Medoo\Medoo;

class Folder implements MedooPersistable
{

    /**
     * @param $storedData
     * @return Entities\Folder
     * @throws \Exception
     */
    public static function fromAssocArray($storedData)
    {
        $folder = new Entities\Folder();

        $folder->setIdentifier($storedData["FolderId"]);
        $folder->setFoldername($storedData["FolderName"]);
        $folder->setAuthor($storedData["FolderAuthor"]);
        $folder->setCreatedAt(new DateTime($storedData["FolderCreatedAt"]));
        $folder->setUpdatedAt(new DateTime($storedData["FolderUpdatedAt"]));

        return $folder;
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return "Archive_Folders";
    }

    /**
     * @return string[]
     */
    public static function getColumnNames()
    {
        return [
            "FolderId" => Medoo::raw("<folder.Id>"),
            "FolderName" => Medoo::raw("<folder.FolderName>"),
            "FolderAuthor" => Medoo::raw("<folder.Author>"),
            "FolderCreatedAt" => Medoo::raw("<folder.CreatedAt>"),
            "FolderUpdatedAt" => Medoo::raw("<folder.UpdatedAt>"),
        ];
    }


}