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
            throw new DiceShakerException(
                "DiceCollection has not been set!",
                DiceShakerException::DICE_COLLECTION_MISSING
            );
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
     * @return DiceShaker
     */
    public function keep(DiceSelectorInterface $selector): self
    {
        $this->ifNoDiceCollectionThrowException();

        $this->setDiceCollection($this->getDiceCollection($selector));

        return $this;
    }

    /**
     * @param DiceSelectorInterface $selector
     * @return DiceShaker
     */
    public function discard(DiceSelectorInterface $selector): self
    {
        $this->ifNoDiceCollectionThrowException();

        $currentDice = $this->getDiceCollection()->getDice();
        $diceToDiscard = $this->getDiceCollection($selector)->getDice();

        $diceToKeep = array_udiff(
            $currentDice,
            $diceToDiscard,
            function (Dice $a, Dice $b): int {
                // Not only do we need to compare objects exactly (which could be done with ===),
                // but also need a consistent method of ordering the objects due to how array_udiff works.
                return spl_object_hash($a) <=> spl_object_hash($b);
            }
        );

        $this->setDiceCollection(new DiceCollection($diceToKeep));

        return $this;
    }

    /**
     * @param DiceSelectorInterface $selector
     * @return mixed
     * @throws DiceException
     * @throws DiceShakerException
     */
    public function getSumValue(DiceSelectorInterface $selector = null)
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
     * @see DiceShaker::getSumValue() Alias of getSumValue()
     */
    public function getTotalValue()
    {
        return call_user_func_array([$this, 'getSumValue'], func_get_args());
    }

    /**
     * @param DiceSelectorInterface|null $selector
     * @return int
     * @throws DiceShakerException
     */
    public function getDiceQuantity(DiceSelectorInterface $selector = null): int
    {
        $this->ifNoDiceCollectionThrowException();

        $diceCollection = $this->getDiceCollection($selector);

        return count($diceCollection);
    }

    /**
     * @see DiceShaker::getDiceQuantity() Alias of getDiceQuantity()
     */
    public function getNumberOfDice()
    {
        return call_user_func_array([$this, 'getDiceQuantity'], func_get_args());
    }

    /**
     * @param DiceSelectorInterface|null $selector
     * @return float|int
     * @throws DiceShakerException
     */
    public function getMeanValue(DiceSelectorInterface $selector = null)
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
        ) / count($diceCollection);
    }

    /**
     * @see DiceShaker::getMeanValue() Alias of getMeanValue()
     */
    public function getAverageValue()
    {
        return call_user_func_array([$this, 'getMeanValue'], func_get_args());
    }

    /**
     * @param DiceSelectorInterface|null $selector
     * @return array
     * @throws DiceShakerException
     */
    public function getValues(DiceSelectorInterface $selector = null): array
    {
        $this->ifNoDiceCollectionThrowException();

        $diceCollection = $this->getDiceCollection($selector);

        return array_map(function (Dice $dice) {
            return $dice->getValue();
        }, $diceCollection->getDice());
    }

    /**
     * @param DiceSelectorInterface|null $selector
     * @return mixed
     */
    public function getHighestValue(DiceSelectorInterface $selector = null)
    {
        $this->ifNoDiceCollectionThrowException();

        $values = $this->getValues($selector);

        return max($values);
    }
}
