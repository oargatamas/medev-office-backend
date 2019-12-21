<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 12. 17.
 * Time: 14:08
 */

namespace MedevOffice\Services\File\Actions\Repository;


use Genkgo\ArchiveStream\Archive;
use Genkgo\ArchiveStream\ContentInterface;
use Genkgo\ArchiveStream\FileContent;
use MedevOffice\Services\File\Entities\DriveEntity;
use MedevOffice\Services\File\Entities\File;
use MedevOffice\Services\File\Entities\Folder;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;

class CompressItems extends APIRepositoryAction
{
    const ITEMS = "ArchiveItems";

    /**
     * @param $args
     * @return Archive
     */
    public function handleRequest($args = [])
    {
        /** @var DriveEntity[] $itemsToCompress */
        $itemsToCompress = $args[self::ITEMS];

        $archive = new Archive();

        $archiveContent = [];

        $this->mapItemsToArchiveElements($itemsToCompress,$archiveContent);

        foreach ($archiveContent as $content){
            $archive = $archive->withContent($content);
        }

        return $archive;
    }


    /**
     * @param DriveEntity[] $driveItems
     * @param ContentInterface[] $output
     * @param string $directoryBase
     */
    private function mapItemsToArchiveElements($driveItems, &$output, $directoryBase = "/")
    {
        foreach ($driveItems as $item){
            if($item instanceof File){
                $output[] = new FileContent($directoryBase.$item->getName(),$item->getFullPath());
            }else{
                /** @var Folder $folder */
                $folder = $item;
                $content = $folder->getContent() ?? [];
                $this->mapItemsToArchiveElements($content, $output,$directoryBase.$folder->getName()."/");
            }
        }
    }
}