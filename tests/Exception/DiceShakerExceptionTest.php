<?php

namespace daverichards00\DiceRollerTest\Exception;

use daverichards00\DiceRoller\Exception\DiceException;
use daverichards00\DiceRoller\Exception\DiceShakerException;
use PHPUnit\Framework\TestCase;

class DiceShakerExceptionTest extends TestCase
{
    public function testDiceShakerExceptionExtendsDiceException()
    {
        $sut = new DiceShakerException();

        $this->assertInstanceOf(DiceException::class, $sut);
    }
}
