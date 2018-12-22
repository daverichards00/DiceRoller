<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\LowestSelector;
use InvalidArgumentException;

class LowestSelectorTest extends DiceSelectorTestCase
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
        $inputDiceArray = $this->createDiceArrayFromValues([2, 5, 3, 6, 1, 4, 2]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[4], // 1
            $inputDiceArray[0], // 2
            $inputDiceArray[6], // 2
            $inputDiceArray[2], // 3
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new LowestSelector($inputCount);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }
}
