<?php

namespace daverichards00\DiceRoller\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;

interface DiceSelectorInterface
{
    /**
     * @param DiceCollection $diceCollection
     * @return DiceCollection
     */
    public function select(DiceCollection $diceCollection): DiceCollection;
}
