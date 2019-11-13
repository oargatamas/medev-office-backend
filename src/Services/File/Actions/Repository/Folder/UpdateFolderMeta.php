<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 14:16
 */

namespace MedevOffice\Services\File\Actions\Repository\Folder;


use DateTime;
use MedevOffice\Services\File\Entities\Persistables\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Database\Medoo\MedooDatabase;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class UpdateFolderMeta extends APIRepositoryAction
{
    const FOLDER = "folder";

    /**
     * @param $args
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        /** @var \MedevOffice\Services\File\Entities\Folder $folder */
        $folder = $args[self::FOLDER];

        $this->database->update(Folder::getTableName(),
            [
                "Id" => $folder->getIdentifier(),
                "FolderName" => $folder->getName(),
                "Author" => $folder->getAuthor(),
                "CreatedAt" => $folder->getCreatedAt()->format(MedooDatabase::DEFAULT_DATE_FORMAT),
                "UpdatedAt" => (new DateTime())->format(MedooDatabase::DEFAULT_DATE_FORMAT)
            ],
            [
                "Id" => $folder->getIdentifier()
            ]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("Folder data can not be updated: ".implode(" - ",$result));
        }
    }
}