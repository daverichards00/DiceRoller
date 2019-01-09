<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerGetValuesTest extends DiceShakerTestCase
{
    public function testGetValuesThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getValues();
    }

    public function testGetValuesReturnsValuesForDiceCollection()
    {
        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);
        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn('red');
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(8.8);

        $result = $this->sut->getValues();

        $this->assertSame([2, 'red', 8.8], $result);
    }

    public function testGetValuesReturnsValuesForDiceCollectionSelection()
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
            ->willReturn('red');
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(8.8);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->getValues($selectorMock);

        $this->assertSame([2, 8.8], $result);
    }
}
