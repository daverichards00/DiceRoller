<?php

namespace daverichards00\DiceRoller;

interface RollerInterface
{
    /**
     * @param int $min
     * @param int $max
     * @return int
     * @throws \Exception
     */
    public function roll(int $min, int $max): int;
}
