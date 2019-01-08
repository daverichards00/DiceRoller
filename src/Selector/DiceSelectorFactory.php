<?php

namespace daverichards00\DiceRoller\Selector;

class DiceSelectorFactory
{
    /**
     * @param int $quantity
     * @return HighestSelector
     */
    public static function highest(int $quantity = 1): HighestSelector
    {
        return new HighestSelector($quantity);
    }

    /**
     * @param int $quantity
     * @return LowestSelector
     */
    public static function lowest(int $quantity = 1): LowestSelector
    {
        return new LowestSelector($quantity);
    }

    /**
     * @param mixed $value
     * @param bool $strict
     * @return EqualToSelector
     */
    public static function equalTo($value, bool $strict = false): EqualToSelector
    {
        return new EqualToSelector($value, $strict);
    }

    /**
     * @param mixed $threshold
     * @return LessThanSelector
     */
    public static function lessThan($threshold): LessThanSelector
    {
        return new LessThanSelector($threshold);
    }

    /**
     * @param mixed $threshold
     * @return LessThanOrEqualToSelector
     */
    public static function lessThanOrEqualTo($threshold): LessThanOrEqualToSelector
    {
        return new LessThanOrEqualToSelector($threshold);
    }

    /**
     * @param mixed $threshold
     * @return GreaterThanSelector
     */
    public static function greaterThan($threshold): GreaterThanSelector
    {
        return new GreaterThanSelector($threshold);
    }

    /**
     * @param mixed $threshold
     * @return GreaterThanOrEqualToSelector
     */
    public static function greaterThanOrEqualTo($threshold): GreaterThanOrEqualToSelector
    {
        return new GreaterThanOrEqualToSelector($threshold);
    }

    /**
     * @param int $quantity
     * @return RandomSelector
     */
    public static function random($quantity = 1): RandomSelector
    {
        return new RandomSelector($quantity);
    }
}
