<?php

namespace daverichards00\DiceRoller\Side;

class DiceSide
{
    /** @var mixed */
    private $value;

    /**
     * DiceSide constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->setValue($value);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return DiceSide
     */
    public function setValue($value)
    {
        if (! is_scalar($value)) {
            throw new \InvalidArgumentException(
                sprintf("A Dice Side must be a scalar, %s given", gettype($value))
            );
        }

        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNumeric(): bool
    {
        return is_int($this->value) || is_float($this->value);
    }
}
