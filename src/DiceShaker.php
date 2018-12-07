<?php

namespace daverichards00\DiceRoller;

use daverichards00\DiceRoller\Exception\DiceException;
use daverichards00\DiceRoller\Exception\DiceShakerException;

class DiceShaker
{
    /** @var DiceCollection */
    private $diceCollection;

    /**
     * DiceShaker constructor.
     * @param mixed $dice
     * @param int $quantity
     * @throws \InvalidArgumentException
     */
    public function __construct($dice = null, int $quantity = 1)
    {
        $this->diceCollection = new DiceCollection($dice, $quantity);
    }

    /**
     * @param mixed $dice
     * @param int $quantity
     * @return DiceShaker
     * @throws \InvalidArgumentException
     */
    public function addDice($dice, int $quantity = 1): self
    {
        $this->diceCollection->addDice($dice, $quantity);
        return $this;
    }

    /**
     * @return Dice[]
     */
    public function getDice(): array
    {
        return $this->diceCollection->getDice();
    }

    /**
     * @param string|null $message
     * @return DiceShaker
     * @throws DiceShakerException
     */
    private function diceExistOrThrowException(string $message = null): self
    {
        if (count($this->diceCollection) == 0) {
            throw new DiceShakerException($message ?? "DiceShaker needs to contain at least 1 Dice.");
        }
        return $this;
    }

    /**
     * @param string|null $message
     * @return DiceShaker
     * @throws DiceShakerException
     */
    private function allDiceNumericOrThrowException(string $message = null): self
    {
        if (! $this->diceCollection->isNumeric()) {
            throw new DiceShakerException($message ?? "DiceShaker must contain only numeric Dice.");
        }
        return $this;
    }

    /**
     * @param int $times
     * @return DiceShaker
     * @throws DiceException
     * @throws DiceShakerException
     */
    public function roll($times = 1): self
    {
        $this->diceExistOrThrowException("DiceShaker needs to contain at least 1 Dice to roll.");

        foreach ($this->diceCollection->getDice() as $dice) {
            $dice->roll($times);
        }

        return $this;
    }

    /**
     * @return mixed
     * @throws DiceException
     * @throws DiceShakerException
     */
    public function getSum()
    {

        $this
            ->diceExistOrThrowException()
            ->allDiceNumericOrThrowException("DiceShaker can only sum numeric Dice.");

        $sum = 0;
        foreach ($this->diceCollection->getDice() as $dice) {
            $sum += $dice->getValue();
        }

        return $sum;
    }

    // TODO: Action Methods (Support Selectors)
    // reRoll (alias of roll)
    // discard
    // keep

    // TODO: Value Methods (Support Selectors)
    // getTotal / getSum (+modifier?)
    // getCount
    // getAverage
    // getAllValues: array
    // getHighestValue
    // getLowestValue

    // TODO: Selector Methods
    // highest X
    // lowest X
    // equalTo X
    // lessThan X
    // lessThanOrEqualTo X
    // greaterThan X
    // greaterThanOrEqualTo X
    // random X
}
