<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\AllSelector;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class AllSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new AllSelector;
        $this->assertInstanceOf(AllSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorReturnsTheSameDiceCollection()
    {
        $diceCollectionMock = $this->createMock(DiceCollection::class);

        $sut = new AllSelector;
        $result = $sut->select($diceCollectionMock);

        $this->assertSame($diceCollectionMock, $result);
    }
}
