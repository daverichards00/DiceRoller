<?php

namespace daverichards00\DiceRoller\Collection;

use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\Exception\DiceException;

class DiceCollection implements \Countable
{
    /** @var Dice[] */
    private $dice = [];

    /** @var bool */
    private $isNumeric = true;

    /**
     * DiceCollection constructor.
     * @param Dice[] $dice
     */
    public function __construct(array $dice)
    {
        $this->addMultipleDice($dice);
    }

    /**
     * @param Dice[] $dice
     * @return DiceCollection
     */
    public function addMultipleDice(array $dice): self
    {
        foreach ($dice as $die) {
            $this->addDice($die);
        }
        return $this;
    }

    /**
     * @param Dice $dice
     * @return DiceCollection
     */
    public function addDice(Dice $dice): self
    {
        $this->dice[] = $dice;

        if (! $dice->isNumeric()) {
            $this->isNumeric = false;
        }

        return $this;
    }

    /**
     * @return Dice[]
     */
    public function getDice(): array
    {
        return $this->dice;
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return $this->isNumeric;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->dice);
    }

    /**
     * @return mixed
     * @throws DiceException
     */
    public function current()
    {
        return current($this->dice);
    }

    /**
     *
     */
    public function next()
    {
        next($this->dice);
    }

    /**
     * @return int|mixed|null|string
     */
    public function key()
    {
        return key($this->dice);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return null !== $this->key();
    }

    /**
     *
     */
    public function rewind()
    {
        reset($this->dice);
    }
}
