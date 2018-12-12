<?php

namespace daverichards00\DiceRollerTest\Collection;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;
use PHPUnit\Framework\TestCase;

class DiceCollectionTest extends TestCase
{
    /** @var DiceCollection */
    protected $sut;

    protected $mockDiceArray;

    public function setUp()
    {
        parent::setUp();

        $this->mockDiceArray = [
            $this->createMock(Dice::class),
            $this->createMock(Dice::class),
            $this->createMock(Dice::class),
        ];

        $this->mockDiceArray[0]->expects($this->any())->method('isNumeric')->willReturn(true);
        $this->mockDiceArray[1]->expects($this->any())->method('isNumeric')->willReturn(true);
        $this->mockDiceArray[2]->expects($this->any())->method('isNumeric')->willReturn(true);

        $this->sut = new DiceCollection($this->mockDiceArray);
    }

    public function testDiceCanBeCounted()
    {
        $this->assertSame(count($this->mockDiceArray), $this->sut->count());
    }

    public function testDiceCollectionIsCountable()
    {
        $this->assertInstanceOf(\Countable::class, $this->sut);
        $this->assertSame(count($this->mockDiceArray), count($this->sut));
    }

    public function testDiceCollectionIsIterable()
    {
        $this->assertInstanceOf(\Iterator::class, $this->sut);

        $result = [];
        foreach ($this->sut as $dice) {
            $result[] = $dice;
        }
        $this->assertSame($this->mockDiceArray, $result);
    }

    public function testDiceCanBeAdded()
    {
        $countBeforeAddition = count($this->sut);

        $diceMock = $this->createMock(Dice::class);
        $this->sut->addDice($diceMock);

        $this->assertSame($countBeforeAddition + 1, count($this->sut));
    }

    public function testMultipleDiceCanBeAdded()
    {
        $countBeforeAddition = count($this->sut);

        $diceMockOne = $this->createMock(Dice::class);
        $diceMockTwo = $this->createMock(Dice::class);
        $this->sut->addMultipleDice([
            $diceMockOne,
            $diceMockTwo
        ]);

        $this->assertSame($countBeforeAddition + 2, count($this->sut));
    }

    public function testDiceCanBeRetrieved()
    {
        $result = $this->sut->getDice();

        $this->assertSame($this->mockDiceArray, $result);

        $diceMock = $this->createMock(Dice::class);
        $this->sut->addDice($diceMock);

        $result = $this->sut->getDice();

        $expected = array_merge($this->mockDiceArray, [$diceMock]);
        $this->assertSame($expected, $result);
    }

    public function testIsNumericIsSetCorrectly()
    {
        $this->assertTrue($this->sut->isNumeric());

        $diceMockNonNumeric = $this->createMock(Dice::class);
        $diceMockNonNumeric
            ->expects($this->once())
            ->method('isNumeric')
            ->willReturn(false);

        $this->sut->addDice($diceMockNonNumeric);

        $this->assertFalse($this->sut->isNumeric());
    }
}
