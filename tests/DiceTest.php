<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\RollerInterface;
use daverichards00\DiceRoller\Rollers\QuickRoller;
use PHPUnit\Framework\TestCase;

class DiceTest extends TestCase
{
    /** @var Dice */
    protected $sut;

    protected $rollerMock;

    public function setUp()
    {
        parent::setUp();

        $this->rollerMock = $this->getMockBuilder(RollerInterface::class)
            ->getMock();

        $this->rollerMock
            ->expects($this->any())
            ->method('roll')
            ->willReturn(1);

        $this->sut = new Dice(20, $this->rollerMock);
    }

    public function testDiceCanBeInstantiated()
    {
        $sut = new Dice(2);
        $this->assertInstanceOf(Dice::class, $sut);
    }

    public function testDiceCanNotBeInstantiatedWithASizeSmallerThan2()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Dice(1);
    }

    public function testDiceDefaultsToUseQuickRoller()
    {
        $sut = new Dice(2);
        $this->assertInstanceOf(QuickRoller::class, $sut->getRoller());
    }

    public function testDiceCanBeInstantiatedWithARoller()
    {
        $this->assertSame($this->rollerMock, $this->sut->getRoller());
    }

    public function testRollerCanBeSet()
    {
        $rollerMock = $this->getMockBuilder(RollerInterface::class)->getMock();

        $this->sut->setRoller($rollerMock);

        $this->assertSame($rollerMock, $this->sut->getRoller());
    }

    public function testValueCanNotBeAccessedWithoutARoll()
    {
        $this->expectException(\RuntimeException::class);
        $result = $this->sut->getValue();
    }

    public function testValueCanBeAccessedAfterARoll()
    {
        $result = $this->sut
            ->roll()
            ->getValue();

        $this->assertSame(1, $result);
    }

    public function testRollUsesTheCorrectRoller()
    {
        $mockOne = $this->getMockBuilder(RollerInterface::class)
            ->getMock();
        $mockOne
            ->expects($this->once())
            ->method('roll')
            ->with(1, 20)
            ->willReturn(5);

        $this->sut->setRoller($mockOne);
        $result = $this->sut->roll()->getValue();

        $this->assertSame(5, $result);

        $mockTwo = $this->getMockBuilder(RollerInterface::class)
            ->getMock();
        $mockTwo
            ->expects($this->once())
            ->method('roll')
            ->with(1, 20)
            ->willReturn(10);

        $this->sut->setRoller($mockTwo);
        $result = $this->sut->roll()->getValue();

        $this->assertSame(10, $result);
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
    public function testRollsMustBeAtLeastOneTime($times)
    {
        $this->rollerMock
            ->expects($this->exactly($times))
            ->method('roll')
            ->willReturn(1);

        $this->sut->roll($times);
    }

    /**
     * @dataProvider invalidRollTimesProvider
     */
    public function testRollsCanNotBeLessThanOneTime($times)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->sut->roll($times);
    }

    public function testHistoryCanBeToggled()
    {
        // Default
        $this->assertFalse($this->sut->isHistoryEnabled());

        // Enable history
        $this->sut->enableHistory();
        $this->assertTrue($this->sut->isHistoryEnabled());

        // Disable history
        $this->sut->disableHistory();
        $this->assertFalse($this->sut->isHistoryEnabled());

        // Enable history
        $this->sut->disableHistory(false);
        $this->assertTrue($this->sut->isHistoryEnabled());

        // Disable history
        $this->sut->enableHistory(false);
        $this->assertFalse($this->sut->isHistoryEnabled());
    }

    public function testHistoryReturnsPreviousRolls()
    {
        $this->sut->enableHistory();

        $sizeOfHistoryToTest = 20;
        $expectedHistory = [];

        for ($i = 0; $i < $sizeOfHistoryToTest; $i++) {
            $expectedHistory[] = $this->sut->roll()->getValue();
        }

        $this->assertSame($expectedHistory, $this->sut->getHistory());
    }

    public function testHistoryCanBeCleared()
    {
        $this->sut->enableHistory();

        $this->sut->roll(6);

        $this->assertSame(6, count($this->sut->getHistory()));

        $this->sut->clearHistory();

        $this->assertSame([], $this->sut->getHistory());
    }

    public function testHistoryCanBeOptional()
    {
        $this->sut->enableHistory()
            ->roll(6);
        $this->assertSame(6, count($this->sut->getHistory()));

        $this->sut->disableHistory()
            ->roll(6);
        $this->assertSame(6, count($this->sut->getHistory()));

        $this->sut->enableHistory()
            ->roll(6);
        $this->assertSame(12, count($this->sut->getHistory()));

        $this->sut->clearHistory()
            ->disableHistory()
            ->roll(6);
        $this->assertSame(0, count($this->sut->getHistory()));
    }
}
