<?php

namespace daverichards00\DiceRoller\Selector;

use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\Collection\DiceCollection;
use InvalidArgumentException;

class LowestSelector implements DiceSelectorInterface
{
    /** @var int */
    private $count;

    public function __construct(int $count = 1)
    {
        if ($count < 1) {
            throw new InvalidArgumentException("Count must be at least 1.");
        }

        $this->count = $count;
    }

    public function select(DiceCollection $diceCollection): DiceCollection
    {
        $dice = $diceCollection->getDice();

        usort($dice, function (Dice $a, Dice $b) {
            return $a->getValue() <=> $b->getValue();
        });

        return new DiceCollection(
            array_slice($dice, 0, $this->count)
        );
    }
}
