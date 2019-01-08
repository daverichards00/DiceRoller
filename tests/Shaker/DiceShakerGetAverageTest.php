<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerGetAverageTest extends DiceShakerTestCase
{
    public function testGetAverageThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getAverage();
    }

    public function testGetAverageThrowsExceptionWhenDiceCollectionNotNumeric()
    {
        $diceCollectionMock = $this->createMock(DiceCollection::class);
        $diceCollectionMock
            ->expects($this->any())
            ->method('isNumeric')
            ->willReturn(false);

        $this->sut->setDiceCollection($diceCollectionMock);

        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_NOT_NUMERIC);
        $this->sut->getAverage();
    }

    public function testGetAverageReturnsTotalForDiceCollection()
    {
        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);
        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(4);
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(6);

        $result = $this->sut->getAverage();

        $this->assertSame(4, $result);
    }

    public function testGetAverageReturnsTotalForDiceCollectionSelection()
    {
        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectedDiceCollectionMock
            ->expects($this->once())
            ->method('getDice')
            ->willReturn([$this->diceArrayMock[0], $this->diceArrayMock[2]]);
        $selectedDiceCollectionMock
            ->expects($this->any())
            ->method('count')
            ->willReturn(2);

        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);
        $this->diceArrayMock[1]
            ->expects($this->never())
            ->method('getValue')
            ->willReturn(4);
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(7);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->getAverage($selectorMock);

        $this->assertSame(4.5, $result);
    }
}
