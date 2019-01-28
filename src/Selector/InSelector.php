<?php

namespace daverichards00\DiceRoller\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;

class InSelector implements DiceSelectorInterface
{
    /** @var array */
    public $values;

    /** @var bool */
    public $strict;

    /**
     * InSelector constructor.
     * @param array $values
     * @param bool $strict
     */
    public function __construct(array $values, bool $strict = false)
    {
        $this->values = $values;
        $this->strict = $strict;
    }

    /**
     * @param DiceCollection $diceCollection
     * @return DiceCollection
     */
    public function select(DiceCollection $diceCollection): DiceCollection
    {
        $dice = $diceCollection->getDice();

        $values = $this->values;
        $strict = $this->strict;

        return new DiceCollection(
            array_filter(
                $dice,
                function (Dice $dice) use ($values, $strict) {
                    return in_array($dice->getValue(), $values, $strict);
                }
            )
        );
    }
}
