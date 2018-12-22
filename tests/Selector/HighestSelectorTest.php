<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\HighestSelector;
use InvalidArgumentException;

class HighestSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new HighestSelector();
        $this->assertInstanceOf(HighestSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorCanBeInstantiatedWithPositiveInt()
    {
        $sut = new HighestSelector(2);
        $this->assertInstanceOf(HighestSelector::class, $sut);
    }

    public function testSelectorThrowsExceptionWhenInstantiatedWith0()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new HighestSelector(0);
    }

    public function testSelectorThrowsExceptionWhenInstantiatedWithNegativeInt()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new HighestSelector(-1);
    }

    public function testSelectorSelectsCorrectDice()
    {
        $inputCount = 4;
        $inputDiceArray = $this->createDiceArrayFromValues([2, 5, 3, 6, 1, 4, 2]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[3], // 6
            $inputDiceArray[1], // 5
            $inputDiceArray[5], // 4
            $inputDiceArray[2], // 3
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new HighestSelector($inputCount);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }
}
