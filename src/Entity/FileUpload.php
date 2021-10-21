<?php

namespace App\Entity;

use App\Model\Timestampable\TimestampableInterface;
use App\Model\Timestampable\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * FileUpload.
 *
 * @ORM\Entity()
 */
class FileUpload implements TimestampableInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")*
     */
    private string $repositoryName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")*
     */
    private string $commitName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $hash;

    /**
     * @var int|null
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $ciUploadId = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isProcessed = false;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $status = null;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="fileUpload", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $files;

    /**
     * @param string $repositoryName
     * @param string $commitName
     */
    public function __construct(string $repositoryName, string $commitName)
    {
        $this->hash = (Uuid::v1())->toBase58();
        $this->files = new ArrayCollection();
        $this->repositoryName = $repositoryName;
        $this->commitName = $commitName;
        $this->initTimestampableFields();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRepositoryName(): string
    {
        return $this->repositoryName;
    }

    /**
     * @return string
     */
    public function getCommitName(): string
    {
        return $this->commitName;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return int|null
     */
    public function getCiUploadId(): ?int
    {
        return $this->ciUploadId;
    }

    /**
     * @param int|null $ciUploadId
     *
     * @return self
     */
    public function setCiUploadId(?int $ciUploadId): self
    {
        $this->ciUploadId = $ciUploadId;

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
     * @return array|null
     */
    public function getStatus(): ?array
    {
        return $this->status;
    }

    /**
     * @param array|null $status
     *
     * @return self
     */
    public function setStatus(?array $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Checks if all files of current upload were processed via API
     *
     * @return bool
     */
    public function allFilesAreProcessed(): bool
    {
        foreach ($this->files as $file) {
            if (false === $file->isProcessed()) {
                return false;
            }
        }

        return true;
    }
}
