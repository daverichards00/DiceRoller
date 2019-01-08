<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\GreaterThanSelector;

class GreaterThanSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new GreaterThanSelector(2);
        $this->assertInstanceOf(GreaterThanSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorSelectsNumbersCorrectly()
    {
        $threshold = 3;
        $inputDiceArray = $this->createDiceArrayFromValues([2, 5, 3, 6, 3, 4, 2, 4, 5, 3, 1]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[1], // 5
            $inputDiceArray[3], // 6
            $inputDiceArray[5], // 4
            $inputDiceArray[7], // 4
            $inputDiceArray[8], // 5
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new GreaterThanSelector($threshold);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }

    public function testSelectorSelectsDecimalsCorrectly()
    {
        $threshold = .3;
        $inputDiceArray = $this->createDiceArrayFromValues([.2, .5, .3, .6, .3, .4, .2, .4, .5, .3, .1]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[1], // .5
            $inputDiceArray[3], // .6
            $inputDiceArray[5], // .4
            $inputDiceArray[7], // .4
            $inputDiceArray[8], // .5
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new GreaterThanSelector($threshold);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }

    public function testSelectorSelectsStringsCorrectly()
    {
        $threshold = 'purple';
        $inputDiceArray = $this->createDiceArrayFromValues(
            ['red', 'green', 'blue', 'yellow', 'black', 'white', 'purple']
        );
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[0], // red
            $inputDiceArray[3], // yellow
            $inputDiceArray[5], // white
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new GreaterThanSelector($threshold);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }
}
