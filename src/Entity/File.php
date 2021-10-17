<?php

namespace App\Entity;

use App\Model\Timestampable\TimestampableInterface;
use App\Model\Timestampable\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File.
 *
 * @ORM\Entity()
 */
class File implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", name="file_name")
     */
    private string $fileName;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isProcessed = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FileUpload", inversedBy="files")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Assert\NotNull()
     */
    private FileUpload $fileUpload;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->initTimestampableFields();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     *
     * @return self
     */
    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * @return bool
     */
    public function isProcessed(): bool
    {
        return $this->isProcessed;
    }

    /**
     * @param bool $isProcessed
     *
     * @return self
     */
    public function setIsProcessed(bool $isProcessed): self
    {
        $this->isProcessed = $isProcessed;

        return $this;
    }

    /**
     * @return FileUpload
     */
    public function getFileUpload(): FileUpload
    {
        return $this->fileUpload;
    }

    /**
     * @param FileUpload $fileUpload
     *
     * @return self
     */
    public function setFileUpload(FileUpload $fileUpload): self
    {
        $this->fileUpload = $fileUpload;

        return $this;
    }
}
