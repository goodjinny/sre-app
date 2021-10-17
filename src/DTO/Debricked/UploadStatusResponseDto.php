<?php

declare(strict_types=1);

namespace App\DTO\Debricked;

/**
 * UploadStatusResponseDto.
 */
final class UploadStatusResponseDto
{
    /**
     * @var int
     */
    private int $progress;

    /**
     * @var bool
     */
    private bool $vulnerabilitiesFound;

    /**
     * @var bool
     */
    private bool $unaffectedVulnerabilitiesFound;

    /**
     * @var string
     */
    private string $automationsAction;

    /**
     * @var string
     */
    private string $policyEngineAction;

    /**
     * @var AutomationRule[]|array
     */
    private array $automationRules;

    /**
     * @var string
     */
    private string $detailsUrl;

    /**
     * @return int
     */
    public function getProgress(): int
    {
        return $this->progress;
    }

    /**
     * @param int $progress
     *
     * @return self
     */
    public function setProgress(int $progress): self
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVulnerabilitiesFound(): bool
    {
        return $this->vulnerabilitiesFound;
    }

    /**
     * @param bool $vulnerabilitiesFound
     *
     * @return self
     */
    public function setVulnerabilitiesFound(bool $vulnerabilitiesFound): self
    {
        $this->vulnerabilitiesFound = $vulnerabilitiesFound;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUnaffectedVulnerabilitiesFound(): bool
    {
        return $this->unaffectedVulnerabilitiesFound;
    }

    /**
     * @param bool $unaffectedVulnerabilitiesFound
     *
     * @return self
     */
    public function setUnaffectedVulnerabilitiesFound(bool $unaffectedVulnerabilitiesFound): self
    {
        $this->unaffectedVulnerabilitiesFound = $unaffectedVulnerabilitiesFound;

        return $this;
    }

    /**
     * @return string
     */
    public function getAutomationsAction(): string
    {
        return $this->automationsAction;
    }

    /**
     * @param string $automationsAction
     *
     * @return self
     */
    public function setAutomationsAction(string $automationsAction): self
    {
        $this->automationsAction = $automationsAction;

        return $this;
    }

    /**
     * @return string
     */
    public function getPolicyEngineAction(): string
    {
        return $this->policyEngineAction;
    }

    /**
     * @param string $policyEngineAction
     *
     * @return self
     */
    public function setPolicyEngineAction(string $policyEngineAction): self
    {
        $this->policyEngineAction = $policyEngineAction;

        return $this;
    }

    /**
     * @return AutomationRule[]|array
     */
    public function getAutomationRules(): array
    {
        return $this->automationRules;
    }

    /**
     * @param AutomationRule[]|array $automationRules
     *
     * @return self
     */
    public function setAutomationRules(array $automationRules): self
    {
        $this->automationRules = $automationRules;

        return $this;
    }

    /**
     * @return string
     */
    public function getDetailsUrl(): string
    {
        return $this->detailsUrl;
    }

    /**
     * @param string $detailsUrl
     *
     * @return self
     */
    public function setDetailsUrl(string $detailsUrl): self
    {
        $this->detailsUrl = $detailsUrl;

        return $this;
    }
}