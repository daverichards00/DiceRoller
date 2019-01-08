<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerDiscardTest extends DiceShakerTestCase
{
    public function testDiceCanNotBeDiscardedIfDiceCollectionIsEmpty()
    {
        $sut = new DiceShaker();
        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->discard($selectorMock);
    }

    public function testDiceCanBeDiscardedWithSelector()
    {
        $diceToDiscardArray = [
            $this->diceArrayMock[2],
            $this->diceArrayMock[0],
        ];
        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectedDiceCollectionMock
            ->expects($this->once())
            ->method('getDice')
            ->willReturn($diceToDiscardArray);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->with($this->diceCollectionMock)
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->discard($selectorMock);

        $this->assertSame($result, $this->sut);
        $this->assertSame(1, count($this->sut->getDiceCollection()->getDice()));
        $this->assertSame([$this->diceArrayMock[1]], $this->sut->getDiceCollection()->getDice());
    }
}
