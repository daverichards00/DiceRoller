<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerGetHighestValueTest extends DiceShakerTestCase
{
    public function testGetHighestValueThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getHighestValue();
    }

    public function highestValueTestCases()
    {
        return [
            [
                'input' => [
                    3,
                    5.2,
                    9,
                    -999,
                    3.3,
                    11.1,
                    -4,
                ],
                'expected' => 11.1
            ],
            [
                'input' => [
                    'red',
                    'blue',
                    'green',
                ],
                'expected' => 'red'
            ]
        ];
    }

    /**
     * @dataProvider highestValueTestCases
     */
    public function testGetHighestValueReturnsHighestValue($input, $expected)
    {
        $sut = $this->getMockBuilder(DiceShaker::class)
            ->setMethods(['getValues'])
            ->getMock();

        $sut->setDiceCollection($this->createMock(DiceCollection::class));

        $diceSelectorMock = $this->createMock(DiceSelectorInterface::class);

        $sut->expects($this->once())
            ->method('getValues')
            ->with($diceSelectorMock)
            ->willReturn($input);

        $result = $sut->getHighestValue($diceSelectorMock);

        $this->assertSame($expected, $result);
    }
}
