<?php

namespace daverichards00\DiceRoller;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Collection\DiceCollectionFactory;
use daverichards00\DiceRoller\Exception\DiceException;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use InvalidArgumentException;

class DiceShaker
{
    /** @var DiceCollection */
    private $diceCollection;

    /**
     * DiceShaker constructor.
     * @param mixed $dice
     * @param int $quantity
     * @throws InvalidArgumentException
     */
    public function __construct($dice = [], int $quantity = 1)
    {
        $this->diceCollection = DiceCollectionFactory::create($dice, $quantity);
    }

    /**
     * @param mixed $dice
     * @param int $quantity
     * @return DiceShaker
     * @throws InvalidArgumentException
     */
    public function addDice($dice, int $quantity = 1): self
    {
//        $this->diceCollection->addDice($dice, $quantity);
        $this->diceCollection->addDice($dice);
        return $this;
    }

    /**
     * @return DiceCollection
     */
    public function getDiceCollection(): DiceCollection
    {
        return $this->diceCollection;
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
            throw new DiceShakerException($message ?? "DiceShaker must only contain numeric Dice.");
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
     * @param DiceSelectorInterface $selector
     * @return mixed
     * @throws DiceException
     * @throws DiceShakerException
     */
    public function getSum(DiceSelectorInterface $selector = null)
    {
        $this
            ->diceExistOrThrowException()
            ->allDiceNumericOrThrowException("DiceShaker can only sum numeric Dice.");

        $diceCollection = $this->getDiceCollection();

        if (! empty($selector)) {
            $diceCollection = $selector->select($diceCollection);
        }

        return array_reduce($diceCollection->getDice(), function ($carry, Dice $dice) {
            return $carry + $dice->getValue();
        }, 0);
    }
}
