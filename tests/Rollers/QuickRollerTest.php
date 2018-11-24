<?php

namespace daverichards00\DiceRollerTest\Rollers;

use daverichards00\DiceRoller\RollerInterface;
use daverichards00\DiceRoller\Rollers\QuickRoller;
use PHPUnit\Framework\TestCase;

class QuickRollerTest extends TestCase
{
    /** @var QuickRoller */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new QuickRoller();
    }

    public function testImplementsRollerInterface()
    {
        $this->assertInstanceOf(RollerInterface::class, $this->sut);
    }

    public function diceSizeProvider()
    {
        return [
            [2],
            [6],
            [20],
            [100]
        ];
    }

    /**
     * @dataProvider diceSizeProvider
     */
    public function testRollReturnsValuesWithinCorrectRange($size)
    {
        $numberOfTests = $size * 20;

        for ($i = 0; $i < $numberOfTests; $i++) {
            $value = $this->sut->roll(1, $size);

            $this->assertGreaterThanOrEqual(1, $value);
            $this->assertLessThanOrEqual($size, $value);
        }
    }
}
