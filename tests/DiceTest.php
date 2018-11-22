<?php

namespace daverichards00\DiceRoller\Test;

use daverichards00\DiceRoller\Dice;
use PHPUnit\Framework\TestCase;

class DiceTest extends TestCase
{
    public function diceSizeProvider()
    {
        return [
            [2],
            [6],
            [20],
            [100]
        ];
    }

    public function testDiceCanBeInstantiated()
    {
        $dice = new Dice(2);
        $this->assertInstanceOf(Dice::class, $dice);
    }

    public function testDiceCanNotBeInstantiatedWithASizeSmallerThan2()
    {
        $this->expectException(\InvalidArgumentException::class);
        $dice = new Dice(1);
    }

    public function testStrongFlagCanBeSet()
    {
        $dice = new Dice(2);

        $dice->strong(true);
        $this->assertTrue($dice->isStrong());

        $dice->strong(false);
        $this->assertFalse($dice->isStrong());

        $dice->strong();
        $this->assertTrue($dice->isStrong());
    }

    public function testQuickFlagCanBeSet()
    {
        $dice = new Dice(2);

        $dice->quick(true);
        $this->assertTrue($dice->isQuick());

        $dice->quick(false);
        $this->assertFalse($dice->isQuick());

        $dice->quick();
        $this->assertTrue($dice->isQuick());
    }

    public function testDefaultStrongValue()
    {
        $dice = new Dice(2);

        $this->assertFalse($dice->isStrong());
        $this->assertTrue($dice->isQuick());
    }

    public function testValueCanNotBeAccessedWithoutARoll()
    {
        $dice = new Dice(2);

        $this->expectException(\RuntimeException::class);
        $result = $dice->getValue();
    }

    public function testValueCanBeAccessedAfterARoll()
    {
        $dice = new Dice(2);

        $dice->roll();
        $result = $dice->getValue();

        $this->assertGreaterThanOrEqual(1, $result);
        $this->assertLessThanOrEqual(2, $result);
    }

    /**
     * @dataProvider diceSizeProvider
     */
    public function testStrongRollReturnsValuesWithinCorrectRange($size)
    {
        $numberOfTests = $size * 20;

        $dice = new Dice($size);

        for ($i = 0; $i < $numberOfTests; $i++) {
            $value = $dice
                ->strongRoll()
                ->getValue();

            $this->assertGreaterThanOrEqual(1, $value);
            $this->assertLessThanOrEqual($size, $value);
        }
    }

    /**
     * @dataProvider diceSizeProvider
     */
    public function testQuickRollReturnsValuesWithinCorrectRange($size)
    {
        $numberOfTests = $size * 20;

        $dice = new Dice($size);

        for ($i = 0; $i < $numberOfTests; $i++) {
            $value = $dice
                ->quickRoll()
                ->getValue();

            $this->assertGreaterThanOrEqual(1, $value);
            $this->assertLessThanOrEqual($size, $value);
        }
    }

    /**
     * @dataProvider diceSizeProvider
     */
    public function testRollReturnsValuesWithinCorrectRange($size)
    {
        $numberOfTests = $size * 10;

        $dice = new Dice($size);

        $dice->quick();

        for ($i = 0; $i < $numberOfTests; $i++) {
            $value = $dice
                ->roll()
                ->getValue();

            $this->assertGreaterThanOrEqual(1, $value);
            $this->assertLessThanOrEqual($size, $value);
        }

        $dice->strong();

        for ($i = 0; $i < $numberOfTests; $i++) {
            $value = $dice
                ->roll()
                ->getValue();

            $this->assertGreaterThanOrEqual(1, $value);
            $this->assertLessThanOrEqual($size, $value);
        }
    }

    public function validRollTimesProvider()
    {
        return [[1], [2], [3]];
    }

    public function invalidRollTimesProvider()
    {
        return [[0], [-1], [-2]];
    }

    /**
     * @dataProvider validRollTimesProvider
     */
    public function testStrongRollsMustBeAtLeastOneTime($times)
    {
        $dice = new Dice(2);

        $result = $dice->strongRoll($times);

        $this->assertInstanceOf(Dice::class, $result);
    }

    /**
     * @dataProvider invalidRollTimesProvider
     */
    public function testStrongRollsCanNotBeLessThanOneTime($times)
    {
        $dice = new Dice(2);

        $this->expectException(\InvalidArgumentException::class);
        $dice->strongRoll($times);
    }

    /**
     * @dataProvider validRollTimesProvider
     */
    public function testQuickRollsMustBeAtLeastOneTime($times)
    {
        $dice = new Dice(2);

        $result = $dice->quickRoll($times);

        $this->assertInstanceOf(Dice::class, $result);
    }

    /**
     * @dataProvider invalidRollTimesProvider
     */
    public function testQuickRollsCanNotBeLessThanOneTime($times)
    {
        $dice = new Dice(2);

        $this->expectException(\InvalidArgumentException::class);
        $dice->quickRoll($times);
    }

    /**
     * @dataProvider validRollTimesProvider
     */
    public function testRollsMustBeAtLeastOneTime($times)
    {
        $dice = new Dice(2);

        $result = $dice->roll($times);

        $this->assertInstanceOf(Dice::class, $result);
    }

    /**
     * @dataProvider invalidRollTimesProvider
     */
    public function testRollsCanNotBeLessThanOneTime($times)
    {
        $dice = new Dice(2);

        $this->expectException(\InvalidArgumentException::class);
        $dice->roll($times);
    }
}
