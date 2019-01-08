<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\RandomSelector;
use InvalidArgumentException;

class RandomSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new RandomSelector();
        $this->assertInstanceOf(RandomSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorCanBeInstantiatedWithPositiveInt()
    {
        $sut = new RandomSelector(2);
        $this->assertInstanceOf(RandomSelector::class, $sut);
    }

    public function testSelectorThrowsExceptionWhenInstantiatedWith0()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new RandomSelector(0);
    }

    public function testSelectorThrowsExceptionWhenInstantiatedWithNegativeInt()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new RandomSelector(-1);
    }

    public function testSelectorSelectsCorrectDice()
    {
        $inputDiceArray = $this->createDiceArrayFromValues([2, 5, 3, 6, 1, 4, 2]);
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new RandomSelector();
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertCount(1, $result->getDice());

        $arrayIntersect = array_filter($result->getDice(), function ($dice) use ($inputDiceArray) {
            foreach ($inputDiceArray as $inputDice) {
                if ($dice == $inputDice) {
                    return true;
                }
            }
            return false;
        });

        $this->assertCount(1, $arrayIntersect);
    }

    public function testSelectorSelectsCorrectDiceForSpecifiedQuantity()
    {
        $quantity = 3;
        $inputDiceArray = $this->createDiceArrayFromValues([2, 5, 3, 6, 1, 4, 2]);
        $inputDiceCollection = $this->createDiceCollectionMockFromDiceArray($inputDiceArray);

        $sut = new RandomSelector($quantity);
        $result = $sut->select($inputDiceCollection);

        $this->assertInstanceOf(DiceCollection::class, $result);
        $this->assertCount($quantity, $result->getDice());

        $arrayIntersect = array_filter($result->getDice(), function ($dice) use ($inputDiceArray) {
            foreach ($inputDiceArray as $inputDice) {
                if ($dice == $inputDice) {
                    return true;
                }
            }
            return false;
        });

        $this->assertCount($quantity, $arrayIntersect);
    }
}
