<?php

namespace daverichards00\DiceRoller;

class Dice
{
    /** @var int */
    private $size;

    /** @var int */
    private $value;

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
     * @param bool $quick
     * @return Dice
     */
    public function quick(bool $quick = true): self
    {
        $this->strong = ! $quick;
        return $this;
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
            $this->value = mt_rand(1, $this->size);
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
            $this->value = random_int(1, $this->size);
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
}