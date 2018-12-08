<?php

namespace daverichards00\DiceRoller;

use daverichards00\DiceRoller\Exception\DiceException;

class DiceCollection implements \Countable
{
    /** @var Dice[] */
    private $dice = [];

    /** @var bool */
    private $isNumeric = true;

    /**
     * DiceCollection constructor.
     * @param null $dice
     * @param int $quantity
     */
    public function __construct($dice = null, int $quantity = 1)
    {
        if (null !== $dice) {
            $this->addDice($dice, $quantity);
        }
    }

    /**
     * @param mixed $dice
     * @param int $quantity
     * @return DiceCollection
     * @throws \InvalidArgumentException
     */
    public function addDice($dice, int $quantity = 1): self
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException("Quantity of Dice to add to DiceCollection cannot be less than 1.");
        }

        if (! ($dice instanceof Dice)) {
            $dice = new Dice($dice);
        }

        while ($quantity--) {
            $this->dice[] = clone $dice;
        }

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