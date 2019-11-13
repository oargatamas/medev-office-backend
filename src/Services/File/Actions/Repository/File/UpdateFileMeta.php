<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 31.
 * Time: 13:55
 */

namespace MedevOffice\Services\File\Actions\Repository\File;


use DateTime;
use MedevOffice\Services\File\Entities\Persistables\File;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Database\Medoo\MedooDatabase;
use MedevSlim\Core\Service\Exceptions\InternalServerException;

class UpdateFileMeta extends APIRepositoryAction
{
    const FILE = "newFile";


    /**
     * @param $args
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        /** @var \MedevOffice\Services\File\Entities\File $file */
        $file = $args[self::FILE];

        $this->database->update(File::getTableName(),
            [
                "FileName" => $file->getName(),
                "Author" => $file->getAuthor(),
                "SizeInBytes" => $file->getFileSize(),
                "Path" => $file->getPath(),
                "MimeType" => $file->getMimetype(),
                "UpdatedAt" => (new DateTime())->format(MedooDatabase::DEFAULT_DATE_FORMAT)
            ],
            [
                "Id" => $file->getIdentifier()
            ]
        );

        $result = $this->database->error();
        if(!is_null($result[2])){
            throw new InternalServerException("File data can not be saved: ".implode(" - ",$result));
        }
    }
}