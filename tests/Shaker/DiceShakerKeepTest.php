<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerKeepTest extends DiceShakerTestCase
{
    public function testDiceCanNotBeKeptIfDiceCollectionIsEmpty()
    {
        $sut = new DiceShaker();
        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->keep($selectorMock);
    }

    public function testDiceCanBeKeptWithSelector()
    {
        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectorMock = $this->createMock(DiceSelectorInterface::class);

        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->with($this->diceCollectionMock)
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->keep($selectorMock);

        $this->assertSame($result, $this->sut);
        $this->assertSame($selectedDiceCollectionMock, $this->sut->getDiceCollection());
    }
}
