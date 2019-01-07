<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\LessThanOrEqualToSelector;

class LessThanOrEqualToSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new LessThanOrEqualToSelector(2);
        $this->assertInstanceOf(LessThanOrEqualToSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorSelectsNumbersCorrectly()
    {
        $threshold = 4;
        $inputDiceArray = $this->createDiceArrayFromValues([2, 5, 3, 6, 3, 4, 2, 4, 5, 3, 1]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[0], // 2
            $inputDiceArray[2], // 3
            $inputDiceArray[4], // 3
            $inputDiceArray[5], // 4
            $inputDiceArray[6], // 2
            $inputDiceArray[7], // 4
            $inputDiceArray[9], // 3
            $inputDiceArray[10], // 1
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new LessThanOrEqualToSelector($threshold);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }

    public function testSelectorSelectsDecimalsCorrectly()
    {
        $threshold = .4;
        $inputDiceArray = $this->createDiceArrayFromValues([.2, .5, .3, .6, .3, .4, .2, .4, .5, .3, .1]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[0], // .2
            $inputDiceArray[2], // .3
            $inputDiceArray[4], // .3
            $inputDiceArray[5], // .4
            $inputDiceArray[6], // .2
            $inputDiceArray[7], // .4
            $inputDiceArray[9], // .3
            $inputDiceArray[10], // .1
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new LessThanOrEqualToSelector($threshold);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }

    public function testSelectorSelectsStringsCorrectly()
    {
        $threshold = 'red';
        $inputDiceArray = $this->createDiceArrayFromValues(
            ['red', 'green', 'blue', 'yellow', 'black', 'white', 'purple']
        );
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[0], // red
            $inputDiceArray[1], // green
            $inputDiceArray[2], // blue
            $inputDiceArray[4], // black
            $inputDiceArray[6], // purple
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new LessThanOrEqualToSelector($threshold);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }
}
