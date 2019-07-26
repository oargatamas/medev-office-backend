<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 10:42
 */

namespace MedevOffice\MedevOffice\Services\File\Entities\Persistables;

use DateTime;
use MedevAuth\Services\Auth\OAuth\Entity\Persistables\MedooPersistable;
use MedevOffice\Services\File\Entities;
use Medoo\Medoo;

class File implements MedooPersistable
{

    /**
     * @param $storedData
     * @return Entities\File
     * @throws \Exception
     */
    public static function fromAssocArray($storedData)
    {
        $file = new Entities\File();

        $file->setIdentifier($storedData["FileId"]);
        $file->setFilename($storedData["FileName"]);
        $file->setAuthorId($storedData["FileAuthor"]);
        $file->setFileSize($storedData["FileSizeInBytes"]);
        $file->setPath($storedData["FilePath"]);
        $file->setMimetype($storedData["FileMimeType"]);
        $file->setCreatedAt(new DateTime($storedData["FileCreatedAt"]));
        $file->setUpdatedAt(new DateTime($storedData["FileUpdatedAt"]));
        //Todo add permissions

        return $file;
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return "Archive_Files";
    }

    /**
     * @return string[]
     */
    public static function getColumnNames()
    {
        return [
            "FileId" => Medoo::raw("<file.Id>"),
            "FileName" => Medoo::raw("<file.FileName>"),
            "FileAuthor" => Medoo::raw("<file.Author>"),
            "FileSizeInBytes" => Medoo::raw("<file.SizeInBytes>"),
            "FilePath" => Medoo::raw("<file.Path>"),
            "FileMimeType" => Medoo::raw("<file.MimeType>"),
            "FileCreatedAt" => Medoo::raw("<file.CreatedAt>"),
            "FileUpdatedAt" => Medoo::raw("<file.UpdatedAt>"),
        ];

    }
}