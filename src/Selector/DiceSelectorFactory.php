<?php

namespace daverichards00\DiceRoller\Selector;

class DiceSelectorFactory
{
    public static function lowest(int $count = 1): DiceSelectorInterface
    {
        return new lowestSelector($count);
    }
}