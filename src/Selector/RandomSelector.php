<?php

namespace daverichards00\DiceRoller\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use InvalidArgumentException;

class RandomSelector implements DiceSelectorInterface
{
    /** @var int */
    private $quantity;

    /**
     * RandomSelector constructor.
     * @param int $quantity
     */
    public function __construct(int $quantity = 1)
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException("Quantity must be at least 1.");
        }
        $this->quantity = $quantity;
    }

    /**
     * @param DiceCollection $diceCollection
     * @return DiceCollection
     */
    public function select(DiceCollection $diceCollection): DiceCollection
    {
        $dice = $diceCollection->getDice();

        // shuffle() not suitable for cryptographic purposes
        if (! shuffle($dice)) {
            throw new \RuntimeException("Unable to randomise Dice, shuffle function failed.");
        }

        return new DiceCollection(
            array_slice($dice, 0, $this->quantity)
        );
    }
}
