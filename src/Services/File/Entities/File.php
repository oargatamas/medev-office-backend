<?php
/**
 * Created by PhpStorm.
 * User: OargaTamas
 * Date: 2019. 07. 26.
 * Time: 10:52
 */

namespace MedevOffice\Services\File\Entities;


use DateTime;

class File extends DriveEntity implements \JsonSerializable
{

    const ID = "fileId";

    /**
     * @var string
     */
    private $filename;

    /**
     * @var int
     */
    private $authorId;

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
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param int $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
    }

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
        $this->path = $path;
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
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
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
            "name" => $this->getFilename(),
            "type" => "file",
            "size" => $this->getFileSize(),
            "mimeType" => $this->getMimetype(),
            "author" => $this->getAuthorId(),
            "createdAt" => $this->getCreatedAt()->getTimestamp(),
            "updatedAt" => $this->getUpdatedAt()->getTimestamp(),
            "permissions" => $this->getPermissions() // Todo modify to group the permissions by user
        ];
    }
}