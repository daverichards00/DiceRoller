<?php

namespace daverichards00\DiceRoller\Selector;

class DiceSelectorFactory
{
    /**
     * @param int $count
     * @return DiceSelectorInterface
     */
    public static function highest(int $count = 1): DiceSelectorInterface
    {
        return new HighestSelector($count);
    }

    /**
     * @param int $count
     * @return DiceSelectorInterface
     */
    public static function lowest(int $count = 1): DiceSelectorInterface
    {
        return new LowestSelector($count);
    }

    /**
     * @param mixed $value
     * @param bool $strict
     * @return DiceSelectorInterface
     */
    public static function equalTo($value, bool $strict = false): DiceSelectorInterface
    {
        return new EqualToSelector($value, $strict);
    }
}
