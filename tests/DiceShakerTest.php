<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Collection\DiceCollectionFactory;
use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DiceShakerTest extends TestCase
{
    /** @var DiceShaker */
    protected $sut;

    /** @var DiceCollection|MockObject */
    protected $diceCollectionMock;

    /** @var Dice[]|MockObject[] */
    protected $diceArrayMock;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new DiceShaker();

        $this->diceArrayMock = [
            $this->createMock(Dice::class),
            $this->createMock(Dice::class),
            $this->createMock(Dice::class),
        ];

        $this->diceCollectionMock = $this->createMock(DiceCollection::class);
        $this->diceCollectionMock
            ->expects($this->any())
            ->method('getDice')
            ->willReturn($this->diceArrayMock);
        $this->diceCollectionMock
            ->expects($this->any())
            ->method('count')
            ->willReturn(count($this->diceArrayMock));
        $this->diceCollectionMock
            ->expects($this->any())
            ->method('isNumeric')
            ->willReturn(true);

        $this->sut->setDiceCollection($this->diceCollectionMock);
    }

    public function testDiceShakerCanBeInstantiated()
    {
        $this->assertInstanceOf(DiceShaker::class, $this->sut);
    }

    public function testDiceShakerThrowsAnExceptionWhenTryingToGetAnUnsetDiceCollection()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $result = $sut->getDiceCollection();
    }

    public function testDiceCollectionCanBeRetrieved()
    {
        $result = $this->sut->getDiceCollection();
        $this->assertSame($this->diceCollectionMock, $result);
    }

    public function testDiceCollectionCanBeFilteredWithByASelectorWhenBeingRetrieved()
    {
        $filteredDiceCollectionMock = $this->createMock(DiceCollection::class);
        $diceSelectorMock = $this->createMock(DiceSelectorInterface::class);
        $diceSelectorMock
            ->expects($this->once())
            ->method('select')
            ->with($this->diceCollectionMock)
            ->willReturn($filteredDiceCollectionMock);

        $result = $this->sut->getDiceCollection($diceSelectorMock);
        $this->assertSame($filteredDiceCollectionMock, $result);
    }

    public function testDiceCollectionIsCreatedThroughConstructor()
    {
        $sut = new DiceShaker([2, 3, 4], 2);
        $expectedDiceCollection = DiceCollectionFactory::create([2, 3, 4], 2);

        $this->assertEquals($expectedDiceCollection, $sut->getDiceCollection());
    }

    public function testDiceCanNotBeRolledIfDiceCollectionIsEmpty()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->roll();
    }

    public function testDiceCanBeRolledOnce()
    {
        $expectedTimes = 1;

        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $result = $this->sut->roll();

        $this->assertSame($result, $this->sut);
    }

    public function testDiceCanBeRolledMultipleTimes()
    {
        $expectedTimes = 3;

        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $result = $this->sut->roll(null, 3);

        $this->assertSame($result, $this->sut);
    }

    public function testDiceCollectionSelectionCanBeRolled()
    {
        $expectedTimes = 1;

        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectedDiceCollectionMock
            ->expects($this->once())
            ->method('getDice')
            ->willReturn([$this->diceArrayMock[0], $this->diceArrayMock[2]]);
        $selectedDiceCollectionMock
            ->expects($this->any())
            ->method('count')
            ->willReturn(2);

        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);
        $this->diceArrayMock[1]
            ->expects($this->never())
            ->method('roll');
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('roll')
            ->with($expectedTimes);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->roll($selectorMock);

        $this->assertSame($result, $this->sut);
    }

    public function testDiceCanNotBeKeptIfDiceCollectionIsEmpty()
    {
        $sut = new DiceShaker();
        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->keep($selectorMock);
    }

    public function testDiceCanBeKeptWithSelector()
    {
        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectorMock = $this->createMock(DiceSelectorInterface::class);

        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->with($this->diceCollectionMock)
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->keep($selectorMock);

        $this->assertSame($result, $this->sut);
        $this->assertSame($this->sut->getDiceCollection(), $selectedDiceCollectionMock);
    }

    public function testGetSumThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getSum();
    }

    public function testGetSumThrowsExceptionWhenDiceCollectionNotNumeric()
    {
        $diceCollectionMock = $this->createMock(DiceCollection::class);
        $diceCollectionMock
            ->expects($this->any())
            ->method('isNumeric')
            ->willReturn(false);

        $this->sut->setDiceCollection($diceCollectionMock);

        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_NOT_NUMERIC);
        $this->sut->getSum();
    }

    public function testGetSumReturnsTotalForDiceCollection()
    {
        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);
        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(4);
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(8);

        $result = $this->sut->getSum();

        $this->assertSame(14, $result);
    }

    public function testGetSumReturnsTotalForDiceCollectionSelection()
    {
        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectedDiceCollectionMock
            ->expects($this->once())
            ->method('getDice')
            ->willReturn([$this->diceArrayMock[0], $this->diceArrayMock[2]]);
        $selectedDiceCollectionMock
            ->expects($this->any())
            ->method('count')
            ->willReturn(2);

        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);
        $this->diceArrayMock[1]
            ->expects($this->never())
            ->method('getValue')
            ->willReturn(4);
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(8);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->getSum($selectorMock);

        $this->assertSame(10, $result);
    }

    public function testGetTotalAliasOfGetSum()
    {
        $sut = $this->getMockBuilder(DiceShaker::class)
            ->setMethods(['getSum'])
            ->getMock();

        $selectorMock = $this->createMock(DiceSelectorInterface::class);

        $sut->expects($this->once())
            ->method('getSum')
            ->with($selectorMock, 2)
            ->willReturn(4);

        $result = $sut->getTotal($selectorMock, 2);

        $this->assertSame(4, $result);
    }

    public function testGetCountThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getCount();
    }

    public function testGetCountReturnsTotalForDiceCollection()
    {
        $result = $this->sut->getCount();

        $this->assertSame(count($this->diceArrayMock), $result);
    }

    public function testGetCountReturnsTotalForDiceCollectionSelection()
    {
        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectedDiceCollectionMock
            ->expects($this->once())
            ->method('count')
            ->willReturn(2);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->getCount($selectorMock);

        $this->assertSame(2, $result);
    }

    public function testGetAverageThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getAverage();
    }

    public function testGetAverageThrowsExceptionWhenDiceCollectionNotNumeric()
    {
        $diceCollectionMock = $this->createMock(DiceCollection::class);
        $diceCollectionMock
            ->expects($this->any())
            ->method('isNumeric')
            ->willReturn(false);

        $this->sut->setDiceCollection($diceCollectionMock);

        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_NOT_NUMERIC);
        $this->sut->getAverage();
    }

    public function testGetAverageReturnsTotalForDiceCollection()
    {
        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);
        $this->diceArrayMock[1]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(4);
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(6);

        $result = $this->sut->getAverage();

        $this->assertSame(4, $result);
    }

    public function testGetAverageReturnsTotalForDiceCollectionSelection()
    {
        $selectedDiceCollectionMock = $this->createMock(DiceCollection::class);
        $selectedDiceCollectionMock
            ->expects($this->once())
            ->method('getDice')
            ->willReturn([$this->diceArrayMock[0], $this->diceArrayMock[2]]);
        $selectedDiceCollectionMock
            ->expects($this->any())
            ->method('count')
            ->willReturn(2);

        $this->diceArrayMock[0]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(2);
        $this->diceArrayMock[1]
            ->expects($this->never())
            ->method('getValue')
            ->willReturn(4);
        $this->diceArrayMock[2]
            ->expects($this->once())
            ->method('getValue')
            ->willReturn(7);

        $selectorMock = $this->createMock(DiceSelectorInterface::class);
        $selectorMock
            ->expects($this->once())
            ->method('select')
            ->willReturn($selectedDiceCollectionMock);

        $result = $this->sut->getAverage($selectorMock);

        $this->assertSame(4.5, $result);
    }
}
