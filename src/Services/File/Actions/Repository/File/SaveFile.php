<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 14:51
 */

namespace MedevOffice\Services\File\Actions\Repository\File;


use MedevOffice\Services\File\Actions\Repository\Folder\AssignItemToFolder;
use MedevOffice\Services\File\Actions\Repository\Folder\GetFolderMeta;
use MedevOffice\Services\File\Actions\Repository\Permission\AddItemPermission;
use MedevOffice\Services\File\Entities;
use MedevOffice\Services\File\Entities\File;
use MedevOffice\Services\File\Entities\Persistables\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Utils\UUID\UUID;

class SaveFile extends APIRepositoryAction
{
    const AUTHOR = "authorId";
    const PARENT_FOLDER = "folder";
    const HTTP_FILE = "httpFile";
    const INHERIT_PERMISSIONS = "inheritPermissions";

    /**
     * @param $args
     * @return File
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $file = null;

        $this->database->action(function () use ($args, &$file) {
            $authorId = $args[self::AUTHOR];
            $uploadedItem = $args[self::HTTP_FILE];
            $inheritPermissions = $args[self::INHERIT_PERMISSIONS];
            $now = new \DateTime();


            $file = new File();
            $file->setIdentifier(UUID::v4());
            $file->setName($uploadedItem->getClientFilename());
            $file->setMimetype($uploadedItem->getClientMediaType());
            $file->setFileSize($uploadedItem->getSize());
            $file->setAuthor($authorId);
            $file->setPath($this->config["application"]["drive"]["documentPath"]); //At the moment all files are stored in one folder. I may optimize it later if needed.
            $file->setUpdatedAt($now);
            $file->setCreatedAt($now);

            $parentFolder = (new GetFolderMeta($this->service))->handleRequest([
                GetFolderMeta::FOLDER_ID => $args[self::PARENT_FOLDER]
            ]);

            $itemPermissions = $parentFolder->getPermissions();
            if (!$inheritPermissions) {
                $itemPermissions = Permission::createPermissions($authorId,$authorId,Entities\Permission::AUTHOR);
            }

            (new PersistFileMeta($this->service))->handleRequest([
                PersistFileMeta::FILE => $file
            ]);

            (new AssignItemToFolder($this->service))->handleRequest([
                AssignItemToFolder::FOLDER_ID => $parentFolder->getIdentifier(),
                AssignItemToFolder::ITEM_ID => $file->getIdentifier()
            ]);

            (new SaveFileToDisk($this->service))->handleRequest([
                SaveFileToDisk::ITEM_ID => $file->getIdentifier(),
                SaveFileToDisk::FILE => $uploadedItem
            ]);

            (new AddItemPermission($this->service))->handleRequest([
                AddItemPermission::ITEM_ID => $file->getIdentifier(),
                AddItemPermission::PERMISSIONS => $itemPermissions
            ]);
        });

        return $file;
    }
}