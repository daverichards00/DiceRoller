<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\GreaterThanOrEqualToSelector;

class GreaterThanOrEqualToSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new GreaterThanOrEqualToSelector(2);
        $this->assertInstanceOf(GreaterThanOrEqualToSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorSelectsNumbersCorrectly()
    {
        $threshold = 3;
        $inputDiceArray = $this->createDiceArrayFromValues([2, 5, 3, 6, 3, 4, 2, 4, 5, 3, 1]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[1], // 5
            $inputDiceArray[2], // 3
            $inputDiceArray[3], // 6
            $inputDiceArray[4], // 3
            $inputDiceArray[5], // 4
            $inputDiceArray[7], // 4
            $inputDiceArray[8], // 5
            $inputDiceArray[9], // 3
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new GreaterThanOrEqualToSelector($threshold);
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
            $inputDiceArray[2], // .3
            $inputDiceArray[3], // .6
            $inputDiceArray[4], // .3
            $inputDiceArray[5], // .4
            $inputDiceArray[7], // .4
            $inputDiceArray[8], // .5
            $inputDiceArray[9], // .3
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new GreaterThanOrEqualToSelector($threshold);
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
            $inputDiceArray[6], // purple
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new GreaterThanOrEqualToSelector($threshold);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }
}
