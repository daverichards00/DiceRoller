<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\Selector\AllSelector;
use daverichards00\DiceRoller\Selector\DiceSelectorFactory;
use daverichards00\DiceRoller\Selector\EqualToSelector;
use daverichards00\DiceRoller\Selector\GreaterThanOrEqualToSelector;
use daverichards00\DiceRoller\Selector\GreaterThanSelector;
use daverichards00\DiceRoller\Selector\HighestSelector;
use daverichards00\DiceRoller\Selector\InSelector;
use daverichards00\DiceRoller\Selector\LessThanOrEqualToSelector;
use daverichards00\DiceRoller\Selector\LessThanSelector;
use daverichards00\DiceRoller\Selector\LowestSelector;
use daverichards00\DiceRoller\Selector\RandomSelector;
use daverichards00\DiceRoller\Selector\TheseSelector;
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

    public function testRandomReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::random(2);
        $this->assertInstanceOf(RandomSelector::class, $sut);
    }

    public function testAllReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::all();
        $this->assertInstanceOf(AllSelector::class, $sut);
    }

    public function testTheseReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::these([$this->createMock(Dice::class)]);
        $this->assertInstanceOf(TheseSelector::class, $sut);
    }

    public function testInReturnsCorrectInstance()
    {
        $sut = DiceSelectorFactory::in([1, 2, 3], true);
        $this->assertInstanceOf(InSelector::class, $sut);
    }
}
