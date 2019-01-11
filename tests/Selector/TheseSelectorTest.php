<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use daverichards00\DiceRoller\Selector\TheseSelector;
use InvalidArgumentException;

class TheseSelectorTest extends DiceSelectorTestCase
{
    public function testSelectorCanBeInstantiated()
    {
        $sut = new TheseSelector([$this->createMock(Dice::class)]);
        $this->assertInstanceOf(TheseSelector::class, $sut);
        $this->assertInstanceOf(DiceSelectorInterface::class, $sut);
    }

    public function testSelectorThrowsInvalidArgumentWhenNotArrayOfDice()
    {
        $this->expectException(InvalidArgumentException::class);
        $sut = new TheseSelector([1, 2, 3]);
    }

    public function theseSelectorTestCases()
    {
        $diceA = $this->createMock(Dice::class);
        $diceB = $this->createMock(Dice::class);
        $diceC = $this->createMock(Dice::class);
        $diceD = $this->createMock(Dice::class);
        $diceE = $this->createMock(Dice::class);
        $diceF = $this->createMock(Dice::class);

        return [
            [
                [$diceA, $diceB, $diceC, $diceD, $diceE],
                [$diceA, $diceB, $diceC, $diceD, $diceE],
                [$diceA, $diceB, $diceC, $diceD, $diceE],
            ],
            [
                [$diceA],
                [$diceA, $diceB, $diceC, $diceD, $diceE],
                [$diceA],
            ],
            [
                [$diceB, $diceC, $diceE],
                [$diceA, $diceB, $diceC, $diceD, $diceE],
                [$diceB, $diceC, $diceE],
            ],
            [
                [$diceD, $diceF],
                [$diceA, $diceB, $diceC, $diceD, $diceE],
                [$diceD],
            ],
            [
                [$diceF],
                [$diceA, $diceB, $diceC, $diceD, $diceE],
                [],
            ],
        ];
    }

    /**
     * @dataProvider theseSelectorTestCases
     */
    public function testSelectorReturnsCorrectDice($diceToSelect, $diceCollection, $expected)
    {
        $sut = new TheseSelector($diceToSelect);

        $result = $sut->select($this->createDiceCollectionMockFromDiceArray($diceCollection));

        $this->assertSame($expected, $result->getDice());
    }
}
