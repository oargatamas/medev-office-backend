<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 29.
 * Time: 14:51
 */

namespace MedevOffice\Services\File\Actions\Repository\File;


use MedevOffice\Services\File\Actions\Repository\Folder\AssignItemToFolder;
use MedevOffice\Services\File\Actions\Repository\Permission\AddItemPermission;
use MedevOffice\Services\File\Entities\File;
use MedevOffice\Services\File\Entities\Permission;
use MedevSlim\Core\Action\Repository\APIRepositoryAction;
use MedevSlim\Utils\UUID\UUID;

class SaveFile extends APIRepositoryAction
{
    const AUTHOR = "authorId";
    const PARENT_FOLDER = "folder";
    const HTTP_FILE = "httpFile";

    /**
     * @param $args
     * @return File
     * @throws \Exception
     */
    public function handleRequest($args = [])
    {
        $file = null;

        $this->database->action(function ($database) use ($args, &$file) {
            $folderId = $args[self::PARENT_FOLDER];
            $authorId = $args[self::AUTHOR];
            $uploadedItem = $args[self::HTTP_FILE];
            $now = new \DateTime();


            $file = new File();
            $file->setIdentifier(UUID::v4());
            $file->setFilename($uploadedItem->getClientFilename());
            $file->setMimetype($uploadedItem->getClientMediaType());
            $file->setFileSize($uploadedItem->getSize());
            $file->setAuthorId($authorId);
            $file->setPath(""); //At the moment all files are stored in one folder. I may optimize it later if needed.
            $file->setUpdatedAt($now);
            $file->setCreatedAt($now);


            (new PersistFileMeta($this->service))->handleRequest([
                PersistFileMeta::FILE => $file
            ]);

            (new AssignItemToFolder($this->service))->handleRequest([
                AssignItemToFolder::FOLDER_ID => $folderId,
                AssignItemToFolder::ITEM_ID => $file->getIdentifier()
            ]);

            (new SaveFileToDisk($this->service))->handleRequest([
                SaveFileToDisk::ITEM_ID => $file->getIdentifier(),
                SaveFileToDisk::FILE => $uploadedItem
            ]);

            (new AddItemPermission($this->service))->handleRequest([
                AddItemPermission::ITEM_ID => $file->getIdentifier(),
                AddItemPermission::USER_ID => $authorId,
                AddItemPermission::APPROVAL_ID => $authorId,
                AddItemPermission::PERMISSIONS => [
                    Permission::READ,
                    Permission::DELETE,
                    Permission::UPDATE,
                    Permission::MOVE,
                    Permission::ADD_GRANT,
                    Permission::REMOVE_GRANT
                ],
            ]);
        });

        return $file;
    }
}