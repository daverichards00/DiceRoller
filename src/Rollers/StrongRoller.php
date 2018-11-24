<?php

namespace daverichards00\DiceRoller\Rollers;

use daverichards00\DiceRoller\RollerInterface;

class StrongRoller implements RollerInterface
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     * @throws \Exception
     */
    public function roll(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
