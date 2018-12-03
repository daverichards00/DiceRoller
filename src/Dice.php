<?php

// TODO: Readme

namespace daverichards00\DiceRoller;

use daverichards00\DiceRoller\Rollers;

class Dice
{
    /** @var RollerInterface */
    private $roller;

    /** @var DiceSides */
    private $sides;

    /** @var null|int */
    private $value;

    /** @var bool */
    private $historyEnabled = false;

    /** @var array */
    private $history = [];

    /**
     * Dice constructor.
     * @param mixed $sides
     * @param RollerInterface $roller
     * @throws \InvalidArgumentException
     */
    public function __construct($sides, RollerInterface $roller = null)
    {
        $this->setSides($sides);

        if (empty($roller)) {
            // Default: QuickRoller
            $roller = new Rollers\QuickRoller();
        }
        $this->setRoller($roller);
    }

    /**
     * @param mixed $sides
     * @return Dice
     * @throws \InvalidArgumentException
     */
    public function setSides($sides): self
    {
        if (! ($sides instanceof DiceSides)) {
            $sides = DiceSidesFactory::create($sides);
        }

        if (count($sides) < 2) {
            throw new \InvalidArgumentException("A Dice must have at least 2 sides.");
        }

        $this->sides = $sides;
        return $this;
    }

    /**
     * @return DiceSides
     */
    public function getSides(): DiceSides
    {
        return $this->sides;
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
     * @throws \InvalidArgumentException|DiceException
     */
    public function roll($times = 1): self
    {
        if ($times < 1) {
            throw new \InvalidArgumentException("A Dice must be rolled at least 1 time.");
        }

        $numberOfSides = count($this->sides);

        while ($times--) {
            $this->setValue(
                $this->sides->getByIndex(
                    $this->getRoller()->roll(1, $numberOfSides) - 1
                )
            );
        }

        return $this;
    }

    /**
     * @param DiceSide $diceSide
     * @return Dice
     */
    private function setValue(DiceSide $diceSide): self
    {
        $this->value = $diceSide->getValue();

        if ($this->isHistoryEnabled()) {
            $this->addHistory($diceSide);
        }

        return $this;
    }

    /**
     * @return mixed
     * @throws DiceException
     */
    public function getValue()
    {
        if (is_null($this->value)) {
            throw new DiceException("Cannot get the value of a Dice that hasn't been rolled.");
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
     * @param DiceSide $diceSide
     * @return Dice
     */
    private function addHistory(DiceSide $diceSide): self
    {
        $this->history[] = $diceSide;
        return $this;
    }

    /**
     * @return array
     */
    public function getHistory(): array
    {
        return array_map(function (DiceSide $diceSide) {
            return $diceSide->getValue();
        }, $this->history);
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
