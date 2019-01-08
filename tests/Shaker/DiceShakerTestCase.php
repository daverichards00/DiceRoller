<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\DiceShaker;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DiceShakerTestCase extends TestCase
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
}
