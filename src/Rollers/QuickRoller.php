<?php

namespace daverichards00\DiceRoller\Rollers;

use daverichards00\DiceRoller\RollerInterface;

class QuickRoller implements RollerInterface
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     */
    public function roll(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }
}
