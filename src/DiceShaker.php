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
    public function __construct($dice = null, int $quantity = 1)
    {
        if (! empty($dice)) {
            $this->setDiceCollection(DiceCollectionFactory::create($dice, $quantity));
        }
    }

    /**
     * @param DiceCollection $diceCollection
     * @return DiceShaker
     */
    public function setDiceCollection(DiceCollection $diceCollection): self
    {
        $this->diceCollection = $diceCollection;
        return $this;
    }

    /**
     * @param DiceSelectorInterface $selector
     * @return DiceCollection
     * @throws DiceShakerException
     */
    public function getDiceCollection(DiceSelectorInterface $selector = null): DiceCollection
    {
        if (empty($this->diceCollection)) {
            throw new DiceShakerException("DiceCollection has not been set!");
        }

        $diceCollection = $this->diceCollection;

        if (! empty($selector)) {
            $diceCollection = $selector->select($diceCollection);
        }

        return $diceCollection;
    }

    /**
     * @param string|null $message
     * @return DiceShaker
     * @throws DiceShakerException
     */
    private function ifNoDiceCollectionThrowException(string $message = null): self
    {
        if (empty($this->diceCollection)) {
            throw new DiceShakerException(
                $message ?? "DiceShaker needs to contain a DiceCollection.",
                DiceShakerException::DICE_COLLECTION_MISSING
            );
        }
        return $this;
    }

    /**
     * @param string|null $message
     * @return DiceShaker
     * @throws DiceShakerException
     */
    private function ifDiceCollectionNotNumericThrowException(string $message = null): self
    {
        if (! $this->diceCollection->isNumeric()) {
            throw new DiceShakerException(
                $message ?? "DiceShaker must only contain numeric Dice.",
                DiceShakerException::DICE_COLLECTION_NOT_NUMERIC
            );
        }
        return $this;
    }

    /**
     * @param DiceSelectorInterface $selector
     * @param int $times
     * @return DiceShaker
     * @throws DiceException
     * @throws DiceShakerException
     */
    public function roll(DiceSelectorInterface $selector = null, $times = 1): self
    {
        $this->ifNoDiceCollectionThrowException();

        $diceCollection = $this->getDiceCollection($selector);

        foreach ($diceCollection->getDice() as $dice) {
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
            ->ifNoDiceCollectionThrowException()
            ->ifDiceCollectionNotNumericThrowException("DiceShaker can only sum numeric Dice.");

        $diceCollection = $this->getDiceCollection($selector);

        return array_reduce(
            $diceCollection->getDice(),
            function ($carry, Dice $dice) {
                return $carry + $dice->getValue();
            },
            0
        );
    }

    /**
     * @see DiceShaker::getSum() Alias of getSum()
     */
    public function getTotal()
    {
        return call_user_func_array([$this, 'getSum'], func_get_args());
    }
}
