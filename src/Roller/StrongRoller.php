<?php

namespace daverichards00\DiceRoller\Roller;

use daverichards00\DiceRoller\Exception\DiceException;

class StrongRoller implements RollerInterface
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     * @throws DiceException
     */
    public function roll(int $min, int $max): int
    {
        try {
            return random_int($min, $max);
        } catch (\Error $e) {
            throw new DiceException("Unable to generate a strong random number", $e->getCode(), $e);
        } catch (\Exception $e) {
            throw new DiceException("Unable to generate a strong random number", $e->getCode(), $e);
        }
    }
}
