<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 11:38
 */

namespace MedevOffice\Services\File\Actions\Repository;


use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\Persistables\File;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;
use MedevSlim\Utils\UUID\UUID;

class PersistFileMeta extends APIRepositoryAction
{
    const FILE = "file";

    /**
     * @param $args
     * @return string
     * @throws InternalServerException
     */
    public function handleRequest($args = [])
    {
        /** @var Entities\File $file */
        $file = $args[self::FILE];

        $this->database->insert(File::getTableName(),
            [
                "Id" => $file->getIdentifier(),
                "FileName" => $file->getFilename(),
                "Author" => $file->getAuthorId(),
                "SizeInBytes" => $file->getFileSize(),
                "Path" => $file->getPath(),
                "MimeType" => $file->getMimetype(),
                "CreatedAt" => $file->getCreatedAt(),
                "UpdatedAt" => $file->getUpdatedAt(),
            ]);

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("File data can not be saved: ".implode(" - ",$result));
        }
    }
}