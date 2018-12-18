<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\Side\DiceSide;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DiceSideTest extends TestCase
{
    public function testValuesCanBeSet()
    {
        $sut = new DiceSide(6);
        $this->assertSame(6, $sut->getValue());

        $sut->setValue('Valid Value');
        $this->assertSame('Valid Value', $sut->getValue());

        $sut->setValue(1.2);
        $this->assertSame(1.2, $sut->getValue());
    }

    public function testInvalidArgumentExceptionThrownForNonScalars()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new DiceSide([1, 2, 3]);
    }

    public function testIntValuesTreatedAsNumeric()
    {
        $sut = new DiceSide(6);
        $this->assertTrue($sut->isNumeric());
    }

    public function testFloatValuesTreatedAsNumeric()
    {
        $sut = new DiceSide(2.2);
        $this->assertTrue($sut->isNumeric());
    }

    public function testStringValuesTreatedAsNumeric()
    {
        $sut = new DiceSide('Purple');
        $this->assertFalse($sut->isNumeric());
    }
}
