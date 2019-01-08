<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerRollTest extends DiceShakerTestCase
{
    public function testDiceCanNotBeRolledIfDiceCollectionIsEmpty()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->roll();
    }

    public function testDiceCanBeRolledOnce()
    {
        $expectedTimes = 1;

        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $result = $this->sut->roll();

        $this->assertSame($this->sut, $result);
    }

    public function testDiceCanBeRolledMultipleTimes()
    {
        $expectedTimes = 3;

        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $result = $this->sut->roll(null, 3);

        $this->assertSame($this->sut, $result);
    }

    public function testDiceCollectionSelectionCanBeRolled()
    {
        $expectedTimes = 1;

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
            ->method('roll')
            ->with($expectedTimes);
        $this->diceArrayMock[1]
            ->expects($this->never())
            ->method('roll');
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->roll($selectorMock);

        $this->assertSame($this->sut, $result);
    }
}
