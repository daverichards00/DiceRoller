<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\DiceShaker;
use PHPUnit\Framework\TestCase;

class DiceShakerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testDiceShakerCanBeInstantiated()
    {
        $diceShaker = new DiceShaker();
        $this->assertInstanceOf(DiceShaker::class, $diceShaker);
    }

    public function testDiceCanBeAddedToShaker()
    {
        $diceShaker = new DiceShaker();
        $dice = new Dice(6);
        $diceShaker->addDice($dice);

        $result = $diceShaker->getAllDice();
        $this->assertInstanceOf(Dice::class, $result[0]);
        $this->assertSame(6, count($result[0]->getSides()));
    }

    public function testDiceCanBeAddedToShakerByNumber()
    {
        $diceShaker = new DiceShaker();
        $diceShaker->addDice(20);

        $result = $diceShaker->getAllDice();
        $this->assertInstanceOf(Dice::class, $result[0]);
        $this->assertSame(20, count($result[0]->getSides()));
    }

    public function testCloneOfDiceIsAddedToShaker()
    {
        $diceShaker = new DiceShaker();
        $dice = new Dice(6);
        $diceShaker->addDice($dice);

        $result = $diceShaker->getAllDice();
        $this->assertNotSame($dice, $result[0]);
    }

    public function testMultipleDiceCanBeAddedToShaker()
    {
        $diceShaker = new DiceShaker();
        $dice = new Dice(20);
        $diceShaker->addDice($dice, 3);

        $result = $diceShaker->getAllDice();
        $this->assertSame(3, count($result));

        foreach ($result as $dice) {
            $this->assertInstanceOf(Dice::class, $dice);
            $this->assertSame(20, count($dice->getSides()));
        }
    }

    public function testMultipleDifferentDiceCanBeAddedToShaker()
    {
        $diceShaker = new DiceShaker();

        $diceShaker->addDice(new Dice(20), 2);
        $diceShaker->addDice(new Dice(6), 3);

        $result = $diceShaker->getAllDice();
        $this->assertSame(5, count($result));

        for ($i = 0; $i < 2; $i++) {
            $this->assertInstanceOf(Dice::class, $result[$i]);
            $this->assertSame(20, count($result[$i]->getSides()));
        }

        for ($i = 2; $i < 5; $i++) {
            $this->assertInstanceOf(Dice::class, $result[$i]);
            $this->assertSame(6, count($result[$i]->getSides()));
        }
    }

    public function testDiceCanBeAddedThroughConstructor()
    {
        $diceShaker = new DiceShaker(new Dice(6), 2);

        $result = $diceShaker->getAllDice();
        $this->assertSame(2, count($result));

        foreach ($result as $dice) {
            $this->assertInstanceOf(Dice::class, $dice);
            $this->assertSame(6, count($dice->getSides()));
        }
    }

    public function testExceptionThrownForInvalidQuantity()
    {
        $diceShaker = new DiceShaker();

        $this->expectException(\InvalidArgumentException::class);
        $diceShaker->addDice(6, 0);
    }

    public function testExceptionThrownForInvalidQuantityThroughConstructor()
    {
        $this->expectException(\InvalidArgumentException::class);

        $diceShaker = new DiceShaker(6, 0);
    }
}
