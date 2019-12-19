<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 10:52
 */

namespace MedevOffice\Services\File\Entities;



class File extends DriveEntity implements \JsonSerializable
{

    const ID = "fileId";

    /**
     * @var int
     */
    private $fileSize;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $mimetype;

    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * @param int $fileSize
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $_SERVER["DOCUMENT_ROOT"]."/../".$path;
    }

    /**
     * @return string
     */
    public function getMimetype()
    {
        return $this->mimetype;
    }

    /**
     * @param string $mimetype
     */
    public function setMimetype($mimetype)
    {
        $this->mimetype = $mimetype;
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
            "name" => $this->getName(),
            "type" => "file",
            "size" => $this->getFileSize(),
            "mimeType" => $this->getMimetype(),
            "author" => $this->getAuthor(),
            "createdAt" => $this->getCreatedAt()->getTimestamp(),
            "updatedAt" => $this->getUpdatedAt()->getTimestamp(),
            "permissions" => $this->getPermissionsByUser(),
        ];
    }
}