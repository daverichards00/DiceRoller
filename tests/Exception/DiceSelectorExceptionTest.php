<?php

namespace daverichards00\DiceRollerTest\Exception;

use daverichards00\DiceRoller\Exception\DiceException;
use daverichards00\DiceRoller\Exception\DiceSelectorException;
use PHPUnit\Framework\TestCase;

class DiceSelectorExceptionTest extends TestCase
{
    public function testDiceSelectorExceptionExtendsDiceException()
    {
        $sut = new DiceSelectorException();

        $this->assertInstanceOf(DiceException::class, $sut);
    }
}
