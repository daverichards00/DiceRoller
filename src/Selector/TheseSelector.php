<?php

namespace daverichards00\DiceRoller\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;
use InvalidArgumentException;

class TheseSelector implements DiceSelectorInterface
{
    /** @var Dice[] */
    private $diceToSelect;

    /**
     * TheseSelector constructor.
     * @param Dice[] $diceToSelect
     */
    public function __construct(array $diceToSelect)
    {
        foreach ($diceToSelect as $dice) {
            if (! ($dice instanceof Dice)) {
                throw new InvalidArgumentException("Must be able to select an array of Dice.");
            }
        }
        $this->diceToSelect = $diceToSelect;
    }

    /**
     * @param DiceCollection $diceCollection
     * @return DiceCollection
     */
    public function select(DiceCollection $diceCollection): DiceCollection
    {
        $diceToSelect = $this->diceToSelect;

        return new DiceCollection(
            array_filter(
                $diceCollection->getDice(),
                function (Dice $dice) use ($diceToSelect) {
                    foreach ($diceToSelect as $dieToSelect) {
                        if ($dice === $dieToSelect) {
                            return true;
                        }
                    }
                    return false;
                }
            )
        );
    }
}
