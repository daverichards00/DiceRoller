<?php

// TODO: Custom Dice sides
// TODO: Custom runtime exceptions
// TODO: Readme

namespace daverichards00\DiceRoller;

use daverichards00\DiceRoller\Rollers;

class Dice
{
    /** @var RollerInterface */
    private $roller;

    /** @var int */
    private $size;

    /** @var null|int */
    private $value;

    /** @var bool */
    private $historyEnabled = false;

    /** @var array */
    private $history = [];

    /**
     * Dice constructor.
     * @param int $size
     * @param RollerInterface $roller
     */
    public function __construct(int $size, RollerInterface $roller = null)
    {
        if ($size < 2) {
            throw new \InvalidArgumentException("A Dice must have a size of at least 2");
        }
        $this->size = $size;

        if (empty($roller)) {
            // Default: QuickRoller
            $roller = new Rollers\QuickRoller();
        }
        $this->setRoller($roller);
    }

    /**
     * @param RollerInterface $roller
     * @return Dice
     */
    public function setRoller(RollerInterface $roller): self
    {
        $this->roller = $roller;
        return $this;
    }

    /**
     * @return RollerInterface
     */
    public function getRoller(): RollerInterface
    {
        return $this->roller;
    }

    /**
     * @param int $times
     * @return Dice
     * @throws \Exception
     */
    public function roll($times = 1): self
    {
        if ($times < 1) {
            throw new \InvalidArgumentException("A Dice must be rolled at least 1 time.");
        }

        while ($times--) {
            $this->setValue(
                $this->getRoller()->roll(1, $this->size)
            );
        }

        return $this;
    }

    /**
     * @param int $value
     * @return Dice
     */
    private function setValue(int $value): self
    {
        $this->value = $value;

        if ($this->isHistoryEnabled()) {
            $this->addHistory($this->value);
        }

        return $this;
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function getValue(): int
    {
        if (is_null($this->value)) {
            throw new \RuntimeException("Cannot get the value of a Dice that hasn't been rolled.");
        }

        return $this->value;
    }

    /**
     * @param bool $enabled
     * @return Dice
     */
    public function enableHistory(bool $enabled = true): self
    {
        $this->historyEnabled = $enabled;
        return $this;
    }

    /**
     * @param bool $disabled
     * @return Dice
     */
    public function disableHistory(bool $disabled = true): self
    {
        $this->historyEnabled = ! $disabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isHistoryEnabled(): bool
    {
        return $this->historyEnabled;
    }

    /**
     * @param int $value
     * @return Dice
     */
    private function addHistory(int $value): self
    {
        $this->history[] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getHistory(): array
    {
        return $this->history;
    }

    /**
     * @return Dice
     */
    public function clearHistory(): self
    {
        $this->history = [];
        return $this;
    }
}
