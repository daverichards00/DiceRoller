<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\EqualToSelector;

class EqualToSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new EqualToSelector(2);
        $this->assertInstanceOf(EqualToSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorSelectsCorrectDiceLoosely()
    {
        $value = 3;
        $strict = false;
        $inputDiceArray = $this->createDiceArrayFromValues(['2', 5, 3, 6, '3', 4, 2]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[2], // int(3)
            $inputDiceArray[4], // string(3)
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new EqualToSelector($value, $strict);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }

    public function testSelectorSelectsCorrectDiceStrictly()
    {
        $value = 3;
        $strict = true;
        $inputDiceArray = $this->createDiceArrayFromValues(['2', 5, 3, 6, '3', 4, 2]);
        $expectedOutputDiceCollectionDice = [
            $inputDiceArray[2], // int(3)
        ];
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new EqualToSelector($value, $strict);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertSame($expectedOutputDiceCollectionDice, $result->getDice());
    }
}
