<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerGetValueTest extends DiceShakerTestCase
{
    public function testGetValueThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getValue();
    }

    public function testGetValueThrowsExceptionWhenDiceCollectionContainsMoreThan2Dice()
    {
        $this->expectException(DiceShakerException::class);
        $this->sut->getValue();
    }

    public function testGetValueReturnsValuesForDiceCollection()
    {
        $this->diceArrayMock = [$this->createMock(Dice::class)];
        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);
        $this->diceCollectionMock = $this->createDiceCollectionMockFromDiceArray($this->diceArrayMock, true);
        $this->sut->setDiceCollection($this->diceCollectionMock);

        $result = $this->sut->getValue();

        $this->assertSame(2, $result);
    }

    public function testGetValueReturnsValuesForDiceCollectionSelection()
    {
        $this->diceArrayMock[0]
            ->expects($this->never())
            ->method('getValue')
            ->willReturn(2);
        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn('red');
        $this->diceArrayMock[2]
            ->expects($this->never())
            ->method('getValue')
            ->willReturn(8.8);

        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectedDiceCollectionMock
            ->expects($this->once())
            ->method('getDice')
            ->willReturn([$this->diceArrayMock[1]]);
        $selectedDiceCollectionMock
            ->expects($this->any())
            ->method('count')
            ->willReturn(1);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->getValue($selectorMock);

        $this->assertSame('red', $result);
    }
}
