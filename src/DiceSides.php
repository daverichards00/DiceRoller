<?php

namespace daverichards00\DiceRoller;

class DiceSides implements \Countable, \Iterator
{
    /** @var array */
    private $sides = [];

    /**
     * DiceSides constructor.
     * @param array $sides
     */
    public function __construct(array $sides = [])
    {
        if (! empty($sides)) {
            $this->set($sides);
        }
    }

    /**
     * @param array $sides
     * @return DiceSides
     */
    public function set(array $sides): self
    {
        $this->sides = [];

        foreach ($sides as $side) {
            $this->add($side);
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @return DiceSides
     */
    public function add($value): self
    {
        if (! is_scalar($value)) {
            throw new \InvalidArgumentException(
                sprintf("A Dice Side must be a scalar, %s given", gettype($value))
            );
        }

        $this->sides[] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->sides;
    }

    /**
     * @param int $index
     * @return mixed
     */
    public function get(int $index)
    {
        return $this->sides[$index];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->sides);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->sides);
    }

    /**
     *
     */
    public function next()
    {
        next($this->sides);
    }

    /**
     * @return int|mixed|null|string
     */
    public function key()
    {
        return key($this->sides);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return null !== $this->key();
    }

    /**
     *
     */
    public function rewind()
    {
        reset($this->sides);
    }
}
