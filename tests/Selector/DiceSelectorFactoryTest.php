<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Selector\DiceSelectorFactory;
use daverichards00\DiceRoller\Selector\EqualToSelector;
use daverichards00\DiceRoller\Selector\HighestSelector;
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
}
