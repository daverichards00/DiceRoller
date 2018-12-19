<?php

namespace daverichards00\DiceRollerTest\Collection;

use daverichards00\DiceRoller\Collection\DiceCollectionFactory;
use daverichards00\DiceRoller\Dice;
use PHPUnit\Framework\TestCase;

class DiceCollectionFactoryTest extends TestCase
{
    /**
     * @dataProvider diceInputProvider
     */
    public function testDiceCollectionFactoryCreatesByNumber($diceInput, $quantity, $expectedDiceSides)
    {
        $result = DiceCollectionFactory::create($diceInput, $quantity);

        $expectedCount = $quantity;
        if (is_array($diceInput)) {
            $expectedCount *= count($diceInput);
        }

        $resultDice = $result->getDice();
        $this->assertCount($expectedCount, $resultDice);
        for ($i = 0; $i < count($resultDice); $i++) {
            $this->assertSame($expectedDiceSides[$i], $resultDice[$i]->getSides()->getAllValues());
        }
    }

    public function diceInputProvider()
    {
        return [
            [
                6,
                2,
                [
                    range(1, 6),
                    range(1, 6),
                ]
            ],
            [
                20,
                3,
                [
                    range(1, 20),
                    range(1, 20),
                    range(1, 20),
                ]
            ],
            [
                new Dice([2, 4, 6]),
                4,
                [
                    [2, 4, 6],
                    [2, 4, 6],
                    [2, 4, 6],
                    [2, 4, 6],
                ]
            ],
            [
                new Dice(['red', 'green', 'blue']),
                5,
                [
                    ['red', 'green', 'blue'],
                    ['red', 'green', 'blue'],
                    ['red', 'green', 'blue'],
                    ['red', 'green', 'blue'],
                    ['red', 'green', 'blue'],
                ]
            ],
            [
                [
                    new Dice(6),
                    new Dice(20),
                    new Dice(['a', 'b', 'c']),
                ],
                1,
                [
                    range(1, 6),
                    range(1, 20),
                    ['a', 'b', 'c'],
                ]
            ],
            [
                [
                    new Dice(6),
                    new Dice(20),
                    new Dice(['a', 'b', 'c']),
                ],
                2,
                [
                    range(1, 6),
                    range(1, 6),
                    range(1, 20),
                    range(1, 20),
                    ['a', 'b', 'c'],
                    ['a', 'b', 'c'],
                ]
            ],
        ];
    }

    public function testDiceCollectionFactoryCreatesWithExactDiceInstances()
    {
        $diceInstances = [
            $this->createMock(Dice::class),
            $this->createMock(Dice::class),
            $this->createMock(Dice::class),
        ];

        $result = DiceCollectionFactory::create($diceInstances);

        $this->assertSame($diceInstances, $result->getDice());
    }

    public function testDiceCollectionFactoryClonesDuplicatesOfDice()
    {
        $diceMock = $this->createMock(Dice::class);

        $diceInstances = [$diceMock, $diceMock, $diceMock];

        $result = DiceCollectionFactory::create($diceInstances);

        // Original instance
        $this->assertSame($diceMock, $result->getDice()[0]);

        // Clones of original instance
        $this->assertNotSame($diceMock, $result->getDice()[1]);
        $this->assertEquals($diceMock, $result->getDice()[1]);

        $this->assertNotSame($diceMock, $result->getDice()[2]);
        $this->assertEquals($diceMock, $result->getDice()[2]);
    }

    public function testDiceCollectionFactoryClonesDiceDuplicateByQuantity()
    {
        $diceMock = $this->createMock(Dice::class);

        $result = DiceCollectionFactory::create($diceMock, 3);

        // Original instance
        $this->assertSame($diceMock, $result->getDice()[0]);

        // Clones of original instance
        $this->assertNotSame($diceMock, $result->getDice()[1]);
        $this->assertEquals($diceMock, $result->getDice()[1]);

        $this->assertNotSame($diceMock, $result->getDice()[2]);
        $this->assertEquals($diceMock, $result->getDice()[2]);
    }
}
