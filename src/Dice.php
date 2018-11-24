<?php

// TODO: Custom random functions
// TODO: Custom Dice sides
// TODO: Readme

namespace daverichards00\DiceRoller;

class Dice
{
    /** @var int */
    private $size;

    /** @var null|int */
    private $value;

    /** @var bool */
    private $historyEnabled = false;

    /** @var array */
    private $history = [];

    /** @var bool */
    private $strong = false;

    /**
     * Dice constructor.
     * @param int $size
     */
    public function __construct(int $size)
    {
        if ($size < 2) {
            throw new \InvalidArgumentException("A Dice must have a size of at least 2");
        }

        $this->size = $size;
    }

    /**
     * @param bool $strong
     * @return Dice
     */
    public function strong(bool $strong = true): self
    {
        $this->strong = $strong;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStrong(): bool
    {
        return $this->strong;
    }

    /**
     * @param bool $quick
     * @return Dice
     */
    public function quick(bool $quick = true): self
    {
        $this->strong = ! $quick;
        return $this;
    }

    /**
     * @return bool
     */
    public function isQuick(): bool
    {
        return ! $this->strong;
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

        if ($this->strong) {
            return $this->strongRoll($times);
        }

        return $this->quickRoll($times);
    }

    /**
     * @param int $times
     * @return Dice
     * @throws \Exception
     */
    public function quickRoll(int $times = 1): self
    {
        if ($times < 1) {
            throw new \InvalidArgumentException("A Dice must be rolled at least 1 time.");
        }

        while ($times--) {
            $this->setValue(
                mt_rand(1, $this->size)
            );
        }

        return $this;
    }

    /**
     * @param int $times
     * @return Dice
     * @throws \Exception
     */
    public function strongRoll(int $times = 1): self
    {
        if ($times < 1) {
            throw new \InvalidArgumentException("A Dice must be rolled at least 1 time.");
        }

        while ($times--) {
            $this->setValue(
                random_int(1, $this->size)
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
