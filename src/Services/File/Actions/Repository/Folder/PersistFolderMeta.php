<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 13:45
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use MedevOffice\Services\File\Entities\Persistables\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class PersistFolderMeta extends APIRepositoryAction
{
    const FOLDER = "folder";

    /**
     * @param $args
     * @throws InternalServerException
     */
    public function handleRequest($args = [])
    {
        /** @var \MedevOffice\Services\File\Entities\Folder $folder */
        $folder = $args[self::FOLDER];

        $this->database->insert(Folder::getTableName(),
            [
                "Id" => $folder->getIdentifier(),
                "FolderName" => $folder->getFoldername(),
                "Author" => $folder->getAuthor(),
                "CreatedAt" => $folder->getCreatedAt(),
                "UpdatedAt" => $folder->getUpdatedAt(),
            ]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Item can not be assigned to folder: ".implode(" - ",$result));
        }
    }
}