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

        $this->sut->roll();
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

        $this->sut->roll(3);
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
}
