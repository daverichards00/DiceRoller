<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Collection\DiceCollectionFactory;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerTest extends DiceShakerTestCase
{
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
}
