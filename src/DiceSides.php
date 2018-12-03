<?php

namespace daverichards00\DiceRoller;

class DiceSides implements \Countable, \Iterator
{
    /** @var DiceSide[] */
    private $sides = [];

    /**
     * DiceSides constructor.
     * @param array $sides
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
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
     * @throws \InvalidArgumentException
     */
    public function add($value): self
    {
        $this->sides[] = new DiceSide($value);

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
     * @return array
     */
    public function getAllValues(): array
    {
        return array_map(function (DiceSide $diceSide) {
            return $diceSide->getValue();
        }, $this->sides);
    }

    /**
     * @param int $index
     * @return mixed
     * @throws DiceException
     */
    public function getValue(int $index)
    {
        if (! array_key_exists($index, $this->sides)) {
            throw new DiceException(
                sprintf("DiceSides does not contain a DiceSide at index %d", $index)
            );
        }
        return $this->sides[$index]->getValue();
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
        return current($this->sides)->getValue();
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
