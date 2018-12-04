<?php

namespace daverichards00\DiceRoller\Roller;

use daverichards00\DiceRoller\Exception\DiceException;

interface RollerInterface
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     * @throws DiceException
     */
    public function roll(int $min, int $max): int;
}
