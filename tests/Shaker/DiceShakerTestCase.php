<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRollerTest\DiceTestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DiceShakerTestCase extends DiceTestCase
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

        $this->diceCollectionMock = $this->createDiceCollectionMockFromDiceArray($this->diceArrayMock, true);

        $this->sut->setDiceCollection($this->diceCollectionMock);
    }
}
