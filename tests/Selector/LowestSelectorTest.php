<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\LowestSelector;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LowestSelectorTest extends TestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new LowestSelector();
        $this->assertInstanceOf(LowestSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorCanBeInstantiatedWithPositiveInt()
    {
        $sut = new LowestSelector(2);
        $this->assertInstanceOf(LowestSelector::class, $sut);
    }

    public function testSelectorThrowsExceptionWhenInstantiatedWith0()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new LowestSelector(0);
    }

    public function testSelectorThrowsExceptionWhenInstantiatedWithNegativeInt()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new LowestSelector(-1);
    }

    public function testSelectorSelectsCorrectDice()
    {
        $inputCount = 4;
        $diceA = $this->getDiceMockWithValue(2);
        $diceB = $this->getDiceMockWithValue(5);
        $diceC = $this->getDiceMockWithValue(3);
        $diceD = $this->getDiceMockWithValue(6);
        $diceE = $this->getDiceMockWithValue(1);
        $diceF = $this->getDiceMockWithValue(4);
        $diceG = $this->getDiceMockWithValue(2);
        $inputDiceCollectionDice = [
            $diceA,
            $diceB,
            $diceC,
            $diceD,
            $diceE,
            $diceF,
            $diceG,
        ];
        $expectedOutputDiceCollectionDice = [
            $diceE,
            $diceA,
            $diceG,
            $diceC,
        ];
        $inputDiceCollection = $this->getDiceCollectionMock($inputDiceCollectionDice);

        $sut = new LowestSelector($inputCount);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }

    private function getDiceMockWithValue($value): MockObject
    {
        $diceMock = $this->createMock(Dice::class);
        $diceMock
            ->expects($this->any())
            ->method('getValue')
            ->willReturn($value);
        return $diceMock;
    }

    private function getDiceCollectionMock(array $inputDiceCollectionDice): MockObject
    {
        $inputDiceCollection = $this->createMock(DiceCollection::class);
        $inputDiceCollection
            ->expects($this->once())
            ->method('getDice')
            ->willReturn($inputDiceCollectionDice);
        return $inputDiceCollection;
    }
}
