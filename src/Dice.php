<?php

namespace daverichards00\diceroller;

class Dice
{
    /** @var int */
    private $size;

    /**
     * Dice constructor.
     * @param int $size
     */
    public function __construct(int $size)
    {
        $this->size = $size;
    }
}