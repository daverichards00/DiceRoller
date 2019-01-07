<?php

namespace daverichards00\DiceRoller\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;

class LessThanSelector implements DiceSelectorInterface
{
    /** @var mixed */
    private $threshold;

    /**
     * LessThanSelector constructor.
     * @param mixed $threshold
     */
    public function __construct($threshold)
    {
        $this->threshold = $threshold;
    }

    /**
     * @param DiceCollection $diceCollection
     * @return DiceCollection
     */
    public function select(DiceCollection $diceCollection): DiceCollection
    {
        $dice = $diceCollection->getDice();
        $threshold = $this->threshold;

        return new DiceCollection(
            array_filter(
                $dice,
                function (Dice $dice) use ($threshold) {
                    return $dice->getValue() < $threshold;
                }
            )
        );
    }
}
