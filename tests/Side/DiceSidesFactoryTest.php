<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\Side\DiceSides;
use daverichards00\DiceRoller\Side\DiceSidesFactory;
use PHPUnit\Framework\TestCase;

class DiceSidesFactoryTest extends TestCase
{
    public function testDiceSidesFactoryCanCreateFromInt()
    {
        $sides = DiceSidesFactory::create(6);

        $this->assertInstanceOf(DiceSides::class, $sides);
        $this->assertSame(6, count($sides));
        $this->assertSame([1, 2, 3, 4, 5, 6], $sides->getAllValues());
    }

    public function testDiceSidesFactoryCanCreateFromArrayOfInts()
    {
        $sides = DiceSidesFactory::create([2, 4, 6]);

        $this->assertInstanceOf(DiceSides::class, $sides);
        $this->assertSame(3, count($sides));
        $this->assertSame([2, 4, 6], $sides->getAllValues());
    }

    public function testDiceSidesFactoryCanCreateFromArrayOfStrings()
    {
        $sides = DiceSidesFactory::create(['red', 'blue', 'green']);

        $this->assertInstanceOf(DiceSides::class, $sides);
        $this->assertSame(3, count($sides));
        $this->assertSame(['red', 'blue', 'green'], $sides->getAllValues());
    }

    public function testInvalidArgumentThrownForInvalidParameterType()
    {
        $this->expectException(\InvalidArgumentException::class);

        DiceSidesFactory::create('strings not valid');
    }
}
