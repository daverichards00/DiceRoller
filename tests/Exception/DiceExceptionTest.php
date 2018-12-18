<?php

namespace daverichards00\DiceRollerTest\Exception;

use daverichards00\DiceRoller\Exception\DiceException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DiceExceptionTest extends TestCase
{
    public function testDiceExceptionExtendsRuntimeException()
    {
        $sut = new DiceException();

        $this->assertInstanceOf(RuntimeException::class, $sut);
    }
}
