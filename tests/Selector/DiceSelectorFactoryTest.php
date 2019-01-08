<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Selector\DiceSelectorFactory;
use daverichards00\DiceRoller\Selector\EqualToSelector;
use daverichards00\DiceRoller\Selector\GreaterThanOrEqualToSelector;
use daverichards00\DiceRoller\Selector\GreaterThanSelector;
use daverichards00\DiceRoller\Selector\HighestSelector;
use daverichards00\DiceRoller\Selector\LessThanOrEqualToSelector;
use daverichards00\DiceRoller\Selector\LessThanSelector;
use daverichards00\DiceRoller\Selector\LowestSelector;
use PHPUnit\Framework\TestCase;

class DiceSelectorFactoryTest extends TestCase
{
    public function testHighestReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::highest();
        $this->assertInstanceOf(HighestSelector::class, $sut);
    }

    public function testLowestReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::lowest();
        $this->assertInstanceOf(LowestSelector::class, $sut);
    }

    public function testEqualToReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::equalTo(2);
        $this->assertInstanceOf(EqualToSelector::class, $sut);
    }

    public function testLessThanReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::lessThan(2);
        $this->assertInstanceOf(LessThanSelector::class, $sut);
    }

    public function testLessThanOrEqualToReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::lessThanOrEqualTo(2);
        $this->assertInstanceOf(LessThanOrEqualToSelector::class, $sut);
    }

    public function testGreaterThanReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::greaterThan(2);
        $this->assertInstanceOf(GreaterThanSelector::class, $sut);
    }

    public function testGreaterThanOrEqualToReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::greaterThanOrEqualTo(2);
        $this->assertInstanceOf(GreaterThanOrEqualToSelector::class, $sut);
    }
}
