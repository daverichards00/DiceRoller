<?php

namespace daverichards00\DiceRoller\Exception;

use Throwable;

class DiceShakerException extends DiceException
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
