<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 12. 17.
 * Time: 14:08
 */

namespace MedevOffice\Services\File\Actions\Repository;


use MedevOffice\Services\File\Entities\DriveEntity;
use MedevOffice\Services\File\Entities\File;
use MedevOffice\Services\File\Entities\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Core\Service\Exceptions\InternalServerException;
use ZipArchive;

class CompressItems extends APIRepositoryAction
{
    const ITEMS = "ArchiveItems";

    /**
     * @param $args
     * @return string
     * @throws InternalServerException
     */
    public function handleRequest($args = [])
    {
        $tempFilePath = tempnam(sys_get_temp_dir(),"MD-");
        /** @var DriveEntity[] $itemsToCompress */
        $itemsToCompress = $args[self::ITEMS];

        $zipFile = new ZipArchive();

        if (!$zipFile->open($tempFilePath,ZipArchive::OVERWRITE)) {
            throw new InternalServerException("Cannot open temporary file");
        }

        $this->debug("Zip file status: ".$zipFile->status);

        $this->populateZipFile($zipFile,$itemsToCompress);

        $zipFile->close();

        return $tempFilePath;
    }


    /**
     * @param ZipArchive $zipFile
     * @param DriveEntity[] $driveItems
     * @param string $directoryBase
     */
    private function populateZipFile($zipFile, $driveItems, $directoryBase = "")
    {
        foreach ($driveItems as $item){
            if($item instanceof File){
                $zipFile->addFile($item->getFullPath(),$item->getName());
            }else{
                /** @var Folder $folder */
                $folder = $item;
                $content = $folder->getContent() ?? [];
                $zipFile->addEmptyDir($directoryBase);
                $this->populateZipFile($zipFile,$content,$directoryBase."/".$folder->getName());
            }
        }
    }
}