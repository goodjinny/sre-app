<?php

declare(strict_types=1);

namespace App\DTO\Debricked;

/**
 * AutomationRule.
 */
final class AutomationRule
{
    /**
     * @var string
     */
    private string $ruleDescription;

    /**
     * @var array
     */
    private array $ruleActions;

    /**
     * @var string
     */
    private string $ruleLink;

    /**
     * @var bool
     */
    private bool $hasCves;

    /**
     * @var bool
     */
    private bool $triggered;

    /**
     * @var TriggerEvent[]|array
     */
    private array $triggerEvents;

    /**
     * @return string
     */
    public function getRuleDescription(): string
    {
        return $this->ruleDescription;
    }

    /**
     * @param string $ruleDescription
     *
     * @return self
     */
    public function setRuleDescription(string $ruleDescription): self
    {
        $this->ruleDescription = $ruleDescription;

        return $this;
    }

    /**
     * @return array
     */
    public function getRuleActions(): array
    {
        return $this->ruleActions;
    }

    /**
     * @param array $ruleActions
     *
     * @return self
     */
    public function setRuleActions(array $ruleActions): self
    {
        $this->ruleActions = $ruleActions;

        return $this;
    }

    /**
     * @return string
     */
    public function getRuleLink(): string
    {
        return $this->ruleLink;
    }

    /**
     * @param string $ruleLink
     *
     * @return self
     */
    public function setRuleLink(string $ruleLink): self
    {
        $this->ruleLink = $ruleLink;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasCves(): bool
    {
        return $this->hasCves;
    }

    /**
     * @param bool $hasCves
     *
     * @return self
     */
    public function setHasCves(bool $hasCves): self
    {
        $this->hasCves = $hasCves;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTriggered(): bool
    {
        return $this->triggered;
    }

    /**
     * @param bool $triggered
     *
     * @return self
     */
    public function setTriggered(bool $triggered): self
    {
        $this->triggered = $triggered;

        return $this;
    }

    /**
     * @return TriggerEvent[]|array
     */
    public function getTriggerEvents(): array
    {
        return $this->triggerEvents;
    }

    /**
     * @param TriggerEvent[]|array $triggerEvents
     *
     * @return self
     */
    public function setTriggerEvents(array $triggerEvents): self
    {
        $this->triggerEvents = $triggerEvents;

        return $this;
    }
}