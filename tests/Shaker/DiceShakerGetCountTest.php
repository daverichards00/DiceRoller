<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerGetCountTest extends DiceShakerTestCase
{
    public function testGetCountThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getCount();
    }

    public function testGetCountReturnsTotalForDiceCollection()
    {
        $result = $this->sut->getCount();

        $this->assertSame(count($this->diceArrayMock), $result);
    }

    public function testGetCountReturnsTotalForDiceCollectionSelection()
    {
        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectedDiceCollectionMock
            ->expects($this->once())
            ->method('count')
            ->willReturn(2);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->getCount($selectorMock);

        $this->assertSame(2, $result);
    }
}
