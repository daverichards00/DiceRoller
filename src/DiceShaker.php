<?php

namespace daverichards00\DiceRoller;

use daverichards00\DiceRoller\Exception\DiceException;
use daverichards00\DiceRoller\Exception\DiceShakerException;

class DiceShaker
{
    /** @var Dice[] */
    private $dice;

    /** @var bool */
    private $isNumeric = true;

    /**
     * DiceShaker constructor.
     * @param mixed $dice
     * @param int $quantity
     * @throws \InvalidArgumentException
     */
    public function __construct($dice = null, int $quantity = 1)
    {
        if (null !== $dice) {
            $this->add($dice, $quantity);
        }
    }

    /**
     * @param mixed $dice
     * @param int $quantity
     * @return DiceShaker
     * @throws \InvalidArgumentException
     */
    public function add($dice, int $quantity = 1): self
    {
        if ($quantity < 1) {
            throw new \InvalidArgumentException("Quantity of Dice to add to DiceShaker cannot be less than 1.");
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
     * @param string|null $message
     * @return DiceShaker
     * @throws DiceShakerException
     */
    private function diceExistOrThrowException(string $message = null): self
    {
        if (empty($this->dice)) {
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
        if (! $this->isNumeric) {
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
        $this->diceExistOrThrowException("DiceShaker needs to contain at least 1 Dice before it can roll.");

        foreach ($this->dice as $dice) {
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
        foreach ($this->dice as $dice) {
            $sum += $dice->getValue();
        }

        return $sum;
    }

    // TODO: Action Methods (Support Selectors)
    // reRoll (alias of roll)
    // discard
    // keep

    // TODO: Value Methods (Support Selectors)
    // - total / sum -
    // count
    // average

    // TODO: Selector Methods
    // highest X
    // lowest X
    // equalTo X
    // lessThan X
    // lessThanOrEqualTo X
    // greaterThan X
    // greaterThanOrEqualTo X

    // TODO: Implement \Countable and \Iterator
}
