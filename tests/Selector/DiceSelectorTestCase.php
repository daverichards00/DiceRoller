<?php

namespace daverichards00\DiceRollerTest\Selector;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\Dice;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DiceSelectorTestCase extends TestCase
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
     * @return MockObject
     */
    protected function createDiceCollectionMockFromDiceArray(array $inputDiceCollectionDice): MockObject
    {
        $inputDiceCollection = $this->createMock(DiceCollection::class);
        $inputDiceCollection
            ->expects($this->once())
            ->method('getDice')
        ->willReturn($inputDiceCollectionDice);
        return $inputDiceCollection;
    }
}
