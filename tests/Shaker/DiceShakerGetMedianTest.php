<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;

class DiceShakerGetMedianTest extends DiceShakerTestCase
{
    public function testGetMedianValueThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getHighestValue();
    }

    public function testGetMedianThrowsExceptionWhenDiceCollectionNotNumeric()
    {
        $diceCollectionMock = $this->createMock(DiceCollection::class);
        $diceCollectionMock
            ->expects($this->any())
            ->method('isNumeric')
            ->willReturn(false);

        $this->sut->setDiceCollection($diceCollectionMock);

        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_NOT_NUMERIC);
        $this->sut->getSumValue();
    }

    /**
     * @return array
     */
    public function medianTestCases()
    {
        return [
            [
                [1, 2, 3],
                2
            ],
            [
                [1, 2, 3, 4],
                2.5
            ],
            [
                [11, 2, 40, 9],
                21
            ],
            [
                [11, 2, 40, 9, 4, 88, 298],
                9
            ],
            [
                [37.23, 65.7893, 4.6, 578, 92.364, 25, 9.8, 743],
                335.182
            ],
            [
                [37.23, 65.7893, 4.6, 578, 92.364, 25, 9.8, 743, 1],
                92.364
            ],
        ];
    }

    /**
     * @dataProvider medianTestCases
     */
    public function testGetMedianValueReturnsCorrectValue(array $inputValues, $expected)
    {
        $diceCollectionMock = $this->createDiceCollectionMockFromDiceArray(
            $this->createDiceArrayFromValues($inputValues),
            true
        );
        $this->sut->setDiceCollection($diceCollectionMock);

        $result = $this->sut->getMedianValue();

        $this->assertSame($expected, $result);
    }
}
