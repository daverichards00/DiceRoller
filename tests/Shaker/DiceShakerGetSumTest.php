<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerGetSumTest extends DiceShakerTestCase
{
    public function testGetSumThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getSum();
    }

    public function testGetSumThrowsExceptionWhenDiceCollectionNotNumeric()
    {
        $diceCollectionMock = $this->createMock(DiceCollection::class);
        $diceCollectionMock
            ->expects($this->any())
            ->method('isNumeric')
            ->willReturn(false);

        $this->sut->setDiceCollection($diceCollectionMock);

        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_NOT_NUMERIC);
        $this->sut->getSum();
    }

    public function testGetSumReturnsTotalForDiceCollection()
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
            ->willReturn(8);

        $result = $this->sut->getSum();

        $this->assertSame(14, $result);
    }

    public function testGetSumReturnsTotalForDiceCollectionSelection()
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
            ->willReturn(8);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->getSum($selectorMock);

        $this->assertSame(10, $result);
    }

    public function testGetTotalAliasOfGetSum()
    {
        $sut = $this->getMockBuilder(DiceShaker::class)
            ->setMethods(['getSum'])
            ->getMock();

        $selectorMock = $this->createMock(DiceSelectorInterface::class);

        $sut->expects($this->once())
            ->method('getSum')
            ->with($selectorMock, 2)
            ->willReturn(4);

        $result = $sut->getTotal($selectorMock, 2);

        $this->assertSame(4, $result);
    }
}
