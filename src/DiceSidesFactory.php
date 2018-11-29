<?php

namespace daverichards00\DiceRoller;

class DiceSidesFactory
{
    /**
     * @param mixed $sides
     * @return DiceSides
     * @throws \Exception
     */
    public static function create($sides): DiceSides
    {
        if (is_int($sides)) {
            return new DiceSides(
                range(1, $sides)
            );
        }

        if (is_array($sides)) {
            return new DiceSides($sides);
        }

        throw new \InvalidArgumentException(
            sprintf("DiceSidesFactory requires either an int or an array, %s given.", gettype($sides))
        );
    }
}
