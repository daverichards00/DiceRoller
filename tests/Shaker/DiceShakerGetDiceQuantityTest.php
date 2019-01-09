<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerGetDiceQuantityTest extends DiceShakerTestCase
{
    public function testGetDiceQuantityThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getDiceQuantity();
    }

    public function testGetDiceQuantityReturnsTotalForDiceCollection()
    {
        $result = $this->sut->getDiceQuantity();

        $this->assertSame(count($this->diceArrayMock), $result);
    }

    public function testGetDiceQuantityReturnsTotalForDiceCollectionSelection()
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

        $result = $this->sut->getDiceQuantity($selectorMock);

        $this->assertSame(2, $result);
    }

    public function testGetNumberOfDiceAliasOfGetDiceQuantity()
    {
        $sut = $this->getMockBuilder(DiceShaker::class)
            ->setMethods(['getDiceQuantity'])
            ->getMock();

        $selectorMock = $this->createMock(DiceSelectorInterface::class);

        $sut->expects($this->once())
            ->method('getDiceQuantity')
            ->with($selectorMock, 2)
            ->willReturn(4);

        $result = $sut->getNumberOfDice($selectorMock, 2);

        $this->assertSame(4, $result);
    }
}
