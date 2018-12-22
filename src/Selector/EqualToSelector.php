<?php

namespace daverichards00\DiceRoller\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;

class EqualToSelector implements DiceSelectorInterface
{
    /** @var mixed */
    private $value;

    /** @var bool */
    private $strict;

    /**
     * EqualToSelector constructor.
     * @param mixed $value
     * @param bool $strict
     */
    public function __construct($value, bool $strict = false)
    {
        $this->value = $value;
        $this->strict = $strict;
    }

    /**
     * @param DiceCollection $diceCollection
     * @return DiceCollection
     */
    public function select(DiceCollection $diceCollection): DiceCollection
    {
        $dice = $diceCollection->getDice();

        $value = $this->value;
        $strict = $this->strict;

        return new DiceCollection(
            array_filter(
                $dice,
                function (Dice $dice) use ($value, $strict) {
                    if ($strict) {
                        return $dice->getValue() === $value;
                    }
                    return $dice->getValue() == $value;
                }
            )
        );
    }
}
