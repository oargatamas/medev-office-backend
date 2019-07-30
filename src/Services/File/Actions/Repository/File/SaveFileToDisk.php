<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 11:15
 */

namespace MedevOffice\Services\File\Actions\Repository\File;


use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use Slim\Http\UploadedFile;

class SaveFileToDisk extends APIRepositoryAction
{
    const ITEM_ID = "itemId";
    const FILE = "file";

    /**
     * @param $args
     * @return mixed
     */
    public function handleRequest($args = [])
    {
        /** @var UploadedFile $file */
        $file = $args[self::FILE];
        $fileId = $args[self::ITEM_ID];


        $directory = $_SERVER["DOCUMENT_ROOT"].$this->config["application"]["drive"]["documentPath"];
        $fileName = $fileId;

        $file->moveTo($directory."/".$fileName);
    }
}