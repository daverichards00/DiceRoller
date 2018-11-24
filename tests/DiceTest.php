<?php

namespace daverichards00\DiceRollerTest;

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

    public function testRollUsesTheCorrectRollMethod()
    {
        $dice = $this->getMockBuilder(Dice::class)
            ->setConstructorArgs([2])
            ->setMethods(['strongRoll', 'quickRoll'])
            ->getMock();

        $resultDice = new Dice(2);

        // Roll forwards to strongRoll correctly
        $dice->expects($this->once())
            ->method('strongRoll')
            ->with(10)
            ->willReturn($resultDice);

        $result = $dice->strong()->roll(10);

        $this->assertSame($resultDice, $result);

        // Roll forwards to quickRoll correctly
        $dice->expects($this->once())
            ->method('quickRoll')
            ->with(20)
            ->willReturn($resultDice);

        $result = $dice->quick()->roll(20);

        $this->assertSame($resultDice, $result);
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
        $dice = $this->getMockBuilder(Dice::class)
            ->setConstructorArgs([2])
            ->setMethods(['strongRoll', 'quickRoll'])
            ->getMock();

        // Roll forwards to strongRoll correctly
        $dice->expects($this->once())
            ->method('quickRoll')
            ->willReturn(new Dice(2));

        $result = $dice->quick()->roll($times);
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

    public function testHistoryCanBeToggled()
    {
        $dice = new Dice(6);

        // Default
        $this->assertFalse($dice->isHistoryEnabled());

        // Enable history
        $dice->enableHistory();
        $this->assertTrue($dice->isHistoryEnabled());

        // Disable history
        $dice->disableHistory();
        $this->assertFalse($dice->isHistoryEnabled());

        // Enable history
        $dice->disableHistory(false);
        $this->assertTrue($dice->isHistoryEnabled());

        // Disable history
        $dice->enableHistory(false);
        $this->assertFalse($dice->isHistoryEnabled());
    }

    public function testHistoryReturnsPreviousRolls()
    {
        $dice = new Dice(20);
        $dice->enableHistory();

        $sizeOfHistoryToTest = 20;
        $expectedHistory = [];

        for ($i = 0; $i < $sizeOfHistoryToTest; $i++) {
            $expectedHistory[] = $dice->roll()->getValue();
        }

        $this->assertSame($expectedHistory, $dice->getHistory());
    }

    public function testHistoryCanBeCleared()
    {
        $dice = new Dice(2);
        $dice->enableHistory();

        $dice->roll(6);

        $this->assertSame(6, count($dice->getHistory()));

        $dice->clearHistory();

        $this->assertSame([], $dice->getHistory());
    }

    public function testHistoryCanBeOptional()
    {
        $dice = new Dice(2);

        $dice->enableHistory()
            ->roll(6);
        $this->assertSame(6, count($dice->getHistory()));

        $dice->disableHistory()
            ->roll(6);
        $this->assertSame(6, count($dice->getHistory()));

        $dice->enableHistory()
            ->roll(6);
        $this->assertSame(12, count($dice->getHistory()));

        $dice->clearHistory()
            ->disableHistory()
            ->roll(6);
        $this->assertSame(0, count($dice->getHistory()));
    }
}
