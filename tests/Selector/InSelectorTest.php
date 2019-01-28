<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\InSelector;

class InSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new InSelector([1, 2, 3]);
        $this->assertInstanceOf(InSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorSelectsCorrectDiceLoosely()
    {
        $values = [3, 4];
        $strict = false;
        $inputDiceArray = $this->createDiceArrayFromValues(['2', 5, 3, 6, '3', 4, 2]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[2], // int(3)
            $inputDiceArray[4], // string(3)
            $inputDiceArray[5], // int(4)
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new InSelector($values, $strict);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }

    public function testSelectorSelectsCorrectDiceStrictly()
    {
        $value = [3, 4];
        $strict = true;
        $inputDiceArray = $this->createDiceArrayFromValues(['2', 5, 3, 6, '3', 4, 2]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[2], // int(3)
            $inputDiceArray[5], // int(4)
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new InSelector($value, $strict);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }
}
