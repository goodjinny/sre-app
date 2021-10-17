<?php

declare(strict_types=1);

namespace App\DTO\Debricked;

/**
 * UploadFileResponseDTO.
 */
final class UploadFileResponseDto
{
    /**
     * @var int
     */
    private int $ciUploadId;

    /**
     * @var int
     */
    private int $uploadProgramsFileId;

    /**
     * @return int
     */
    public function getCiUploadId(): int
    {
        return $this->ciUploadId;
    }

    /**
     * @param int $ciUploadId
     *
     * @return self
     */
    public function setCiUploadId(int $ciUploadId): self
    {
        $this->ciUploadId = $ciUploadId;

        return $this;
    }

    /**
     * @return int
     */
    public function getUploadProgramsFileId(): int
    {
        return $this->uploadProgramsFileId;
    }

    /**
     * @param int $uploadProgramsFileId
     *
     * @return self
     */
    public function setUploadProgramsFileId(int $uploadProgramsFileId): self
    {
        $this->uploadProgramsFileId = $uploadProgramsFileId;

        return $this;
    }
}