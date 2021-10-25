<?php

declare(strict_types=1);

namespace App\DTO\Debricked;

/**
 * UploadStatusResponseDto.
 */
final class UploadStatusResponseDto
{
    public const UPLOAD_PROCESSED_PROGRESS_VALUE = 100;

    /**
     * @var int
     */
    private int $progress;

    /**
     * @var int
     */
    private int $vulnerabilitiesFound;

    /**
     * @var int
     */
    private int $unaffectedVulnerabilitiesFound;

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
     * @return int
     */
    public function getVulnerabilitiesFound(): int
    {
        return $this->vulnerabilitiesFound;
    }

    /**
     * @param int $vulnerabilitiesFound
     *
     * @return self
     */
    public function setVulnerabilitiesFound(int $vulnerabilitiesFound): self
    {
        $this->vulnerabilitiesFound = $vulnerabilitiesFound;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVulnerabilitiesFound(): bool
    {
        return $this->vulnerabilitiesFound > 0;
    }

    /**
     * @return int
     */
    public function getUnaffectedVulnerabilitiesFound(): int
    {
        return $this->unaffectedVulnerabilitiesFound;
    }

    /**
     * @param int $unaffectedVulnerabilitiesFound
     *
     * @return self
     */
    public function setUnaffectedVulnerabilitiesFound(int $unaffectedVulnerabilitiesFound): self
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

    /**
     * @return bool
     */
    public function isUploadProcessed(): bool
    {
        return self::UPLOAD_PROCESSED_PROGRESS_VALUE === $this->getProgress();
    }

    /**
     * Returns an array with all found vulnerabilities
     *
     * @return array|TriggerEvent[]
     */
    public function getAllFoundVulnerabilities(): array
    {
        $data = [];

        foreach ($this->getAutomationRules() as $automationRule) {
            if (!$automationRule->isHasCves()) {
                continue;
            }

            foreach ($automationRule->getTriggerEvents() as $triggerEvent) {
                $data[$triggerEvent->getCve()] = $triggerEvent;
            }
        }

        return $data;
    }

    /**
     * Returns all actions contains 'sendEmail' rule action type
     *
     * @return array|AutomationRule[]
     */
    public function getSendEmailActions(): array
    {
        $data = [];

        foreach ($this->getAutomationRules() as $automationRule) {
            if ($automationRule->containsRuleAction(AutomationRule::RULE_ACTION_SEND_EMAIL)) {
                $data[] = $automationRule;
            }
        }

        return $data;
    }
}