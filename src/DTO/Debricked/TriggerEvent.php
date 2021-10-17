<?php

declare(strict_types=1);

namespace App\DTO\Debricked;

/**
 * TriggerEvent.
 */
final class TriggerEvent
{
    /**
     * @var string
     */
    private string $dependency;

    /**
     * @var string
     */
    private string $dependencyLink;

    /**
     * @var array
     */
    private array $licenses;

    /**
     * @var string|null
     */
    private ?string $cve;

    /**
     * @var float|null
     */
    private ?float $cvss2;

    /**
     * @var float|null
     */
    private ?float $cvss3;

    /**
     * @var string
     */
    private string $cveLink;

    /**
     * @return string
     */
    public function getDependency(): string
    {
        return $this->dependency;
    }

    /**
     * @param string $dependency
     *
     * @return self
     */
    public function setDependency(string $dependency): self
    {
        $this->dependency = $dependency;

        return $this;
    }

    /**
     * @return string
     */
    public function getDependencyLink(): string
    {
        return $this->dependencyLink;
    }

    /**
     * @param string $dependencyLink
     *
     * @return self
     */
    public function setDependencyLink(string $dependencyLink): self
    {
        $this->dependencyLink = $dependencyLink;

        return $this;
    }

    /**
     * @return array
     */
    public function getLicenses(): array
    {
        return $this->licenses;
    }

    /**
     * @param array $licenses
     *
     * @return self
     */
    public function setLicenses(array $licenses): self
    {
        $this->licenses = $licenses;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCve(): ?string
    {
        return $this->cve;
    }

    /**
     * @param string|null $cve
     *
     * @return self
     */
    public function setCve(?string $cve): self
    {
        $this->cve = $cve;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getCvss2(): ?float
    {
        return $this->cvss2;
    }

    /**
     * @param float|null $cvss2
     *
     * @return self
     */
    public function setCvss2(?float $cvss2): self
    {
        $this->cvss2 = $cvss2;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getCvss3(): ?float
    {
        return $this->cvss3;
    }

    /**
     * @param float|null $cvss3
     *
     * @return self
     */
    public function setCvss3(?float $cvss3): self
    {
        $this->cvss3 = $cvss3;

        return $this;
    }

    /**
     * @return string
     */
    public function getCveLink(): string
    {
        return $this->cveLink;
    }

    /**
     * @param string $cveLink
     *
     * @return self
     */
    public function setCveLink(string $cveLink): self
    {
        $this->cveLink = $cveLink;

        return $this;
    }
}