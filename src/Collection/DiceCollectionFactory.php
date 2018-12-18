<?php

namespace daverichards00\DiceRoller\Collection;

use daverichards00\DiceRoller\Dice;
use InvalidArgumentException;

class DiceCollectionFactory
{
    /**
     * @param mixed $dice
     * @param int $quantity
     * @return DiceCollection
     */
    public static function create($dice, int $quantity = 1): DiceCollection
    {
        $diceArray = self::generateDiceArray($dice, $quantity);

        return new DiceCollection($diceArray);
    }

    /**
     * @param mixed $dice
     * @param int $quantity
     * @return Dice[]
     */
    private static function generateDiceArray($dice, int $quantity = 1): array
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException("Quantity of Dice to add to DiceCollection cannot be less than 1.");
        }

        $diceArray = [];

        if (is_scalar($dice)) {
            $dice = new Dice($dice);
        }

        if ($dice instanceof Dice) {
            return self::addDiceToArray($diceArray, $dice, $quantity);
        }

        if (is_array($dice)) {
            foreach ($dice as $die) {
                if (! ($die instanceof Dice)) {
                    $die = new Dice($die);
                }
                $diceArray = self::addDiceToArray($diceArray, $die, $quantity);
            }
            return $diceArray;
        }

        throw new InvalidArgumentException(
            "To create a DiceCollection, you must pass either a Scalar, an instance of Dice, "
            ."or an array of either Scalars and/or Dice."
        );
    }

    /**
     * @param Dice[] $diceArray
     * @param Dice $dice
     * @param int $quantity
     * @return Dice[]
     */
    private static function addDiceToArray(array $diceArray, Dice $dice, int $quantity = 1): array
    {
        while ($quantity--) {
            if (in_array($dice, $diceArray, true)) {
                $diceArray[] = clone $dice;
                continue;
            }
            $diceArray[] = $dice;
        }
        return $diceArray;
    }
}
