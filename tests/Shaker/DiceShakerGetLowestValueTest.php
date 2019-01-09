<?php

namespace daverichards00\DiceRollerTest\Shaker;

use daverichards00\DiceRoller\Collection\DiceCollection;
use daverichards00\DiceRoller\DiceShaker;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use daverichards00\DiceRoller\Selector\DiceSelectorInterface;

class DiceShakerGetLowestValueTest extends DiceShakerTestCase
{
    public function testGetHighestValueThrowsExceptionWhenDiceCollectionMissing()
    {
        $sut = new DiceShaker();
        $this->expectException(DiceShakerException::class);
        $this->expectExceptionCode(DiceShakerException::DICE_COLLECTION_MISSING);
        $sut->getLowestValue();
    }

    public function lowestValueTestCases()
    {
        return [
            [
                'input' => [
                    3,
                    5.2,
                    9,
                    999,
                    1.3,
                    11.1,
                    4,
                ],
                'expected' => 1.3
            ],
            [
                'input' => [
                    'red',
                    'blue',
                    'green',
                ],
                'expected' => 'blue'
            ]
        ];
    }

    /**
     * @dataProvider lowestValueTestCases
     */
    public function testGetLowestValueReturnsHighestValue($input, $expected)
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

        $result = $sut->getLowestValue($diceSelectorMock);

        $this->assertSame($expected, $result);
    }
}
