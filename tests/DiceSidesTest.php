<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\DiceSide;
use daverichards00\DiceRoller\DiceSides;
use PHPUnit\Framework\TestCase;

class DiceSidesTest extends TestCase
{
    /** @var DiceSides */
    protected $sut;

    public function setUp()
    {
        $this->sut = new DiceSides(['a', 'b', 'c']);
    }

    public function testIsCountable()
    {
        $result = count($this->sut);
        $this->assertSame(3, $result);
    }

    public function testIsIterable()
    {
        $result = [];
        foreach ($this->sut as $value) {
            $result[] = $value;
        }
        $this->assertSame(['a', 'b', 'c'], $result);
    }

    public function testAllSidesCanBeRetrieved()
    {
        $result = $this->sut->getAll();

        $this->assertInstanceOf(DiceSide::class, $result[0]);
        $this->assertSame('a', $result[0]->getValue());
        $this->assertInstanceOf(DiceSide::class, $result[1]);
        $this->assertSame('b', $result[1]->getValue());
        $this->assertInstanceOf(DiceSide::class, $result[2]);
        $this->assertSame('c', $result[2]->getValue());
    }

    public function testAllValuesCanBeRetrieved()
    {
        $result = $this->sut->getAllValues();
        $this->assertSame(['a', 'b', 'c'], $result);
    }

    public function testSideCanBeRetrievedByIndex()
    {
        $result = $this->sut->getValue(0);
        $this->assertSame('a', $result);

        $result = $this->sut->getValue(2);
        $this->assertSame('c', $result);
    }

    public function testExceptionThrownWhenTryingToGetWithAnInvalidIndex()
    {
        $this->expectException(\RuntimeException::class);
        $this->sut->getValue(999);
    }

    public function testSidesCanBeAdded()
    {
        $this->sut->add('d');
        $this->assertSame(4, count($this->sut));
    }

    public function testAllSidesCanBeSet()
    {
        $expected = ['d', 'e', 'f'];
        $this->sut->set($expected);
        $this->assertSame($expected, $this->sut->getAllValues());

        $expected = [1, 2, 3];
        $this->sut->set($expected);
        $this->assertSame($expected, $this->sut->getAllValues());
    }
}
