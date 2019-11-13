<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 10:52
 */

namespace MedevOffice\Services\File\Entities;



class Folder extends DriveEntity implements \JsonSerializable
{
    const ID = "folderId";


    /**
     * @var DriveEntity[]
     */
    private $content;

    /**
     * @return DriveEntity[]
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param DriveEntity[] $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param DriveEntity $item
     */
    public function addItem($item){
        $this->content[] = $item;
    }


    /**
     * @param DriveEntity $item
     */
    public function removeItem($item){
        $this->content = array_filter($this->content, function(DriveEntity $a) use($item) {
            return $a->getIdentifier() !== $item->getIdentifier();
        });
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            "id" => $this->getIdentifier(),
            "type" => "folder",
            "name" => $this->getName(),
            "author" => $this->getAuthor(),
            "createdAt" => $this->getCreatedAt()->getTimestamp(),
            "updatedAt" => $this->getUpdatedAt()->getTimestamp(),
            "permissions" => $this->getPermissionsByUser(),
            "content" => $this->getContent(),
        ];
    }
}