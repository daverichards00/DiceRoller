<?php

namespace daverichards00\DiceRollerTest;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DiceTestCase extends TestCase
{
    /**
     * @param array $values
     * @return MockObject[]
     */
    protected function createDiceArrayFromValues(array $values): array
    {
        $dice = [];
        foreach ($values as $value) {
            $dice[] = $this->createDiceMockWithValue($value);
        }
        return $dice;
    }

    /**
     * @param $value
     * @return MockObject
     */
    protected function createDiceMockWithValue($value): MockObject
    {
        $diceMock = $this->createMock(Dice::class);
        $diceMock
            ->expects($this->any())
            ->method('getValue')
            ->willReturn($value);
        return $diceMock;
    }

    /**
     * @param array $inputDiceCollectionDice
     * @param bool $isNumeric
     * @return MockObject
     */
    protected function createDiceCollectionMockFromDiceArray(
        array $inputDiceCollectionDice,
        bool $isNumeric = null
    ): MockObject {
        $diceCollection = $this->createMock(DiceCollection::class);
        $diceCollection
            ->expects($this->any())
            ->method('getDice')
            ->willReturn($inputDiceCollectionDice);
        $diceCollection
            ->expects($this->any())
            ->method('count')
            ->willReturn(count($this->diceArrayMock));
        if (null !== $isNumeric) {
            $diceCollection
                ->expects($this->any())
                ->method('isNumeric')
                ->willReturn($isNumeric);
        }
        return $diceCollection;
    }
}
