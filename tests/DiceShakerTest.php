<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Collection\DiceCollectionFactory;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use PHPUnit\Framework\TestCase;

class DiceShakerTest extends TestCase
{
    /** @var DiceShaker */
    protected $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new DiceShaker();
    }

    public function testDiceShakerCanBeInstantiated()
    {
        $this->assertInstanceOf(DiceShaker::class, $this->sut);
    }

    public function testDiceShakerThrowsAnExceptionWhenTryingToGetAnUnsetDiceCollection()
    {
        $this->expectException(DiceShakerException::class);
        $result = $this->sut->getDiceCollection();
    }

    public function testDiceCollectionCanBeSetAndRetrieved()
    {
        $diceCollectionMock = $this->createMock(DiceCollection::class);
        $this->sut->setDiceCollection($diceCollectionMock);

        $result = $this->sut->getDiceCollection();
        $this->assertSame($diceCollectionMock, $result);
    }

    public function testDiceCollectionIsCreatedThroughConstructor()
    {
        $sut = new DiceShaker([2, 3, 4], 2);
        $expectedDiceCollection = DiceCollectionFactory::create([2, 3, 4], 2);

        $this->assertEquals($expectedDiceCollection, $sut->getDiceCollection());
    }
}
