<?php

namespace daverichards00\DiceRoller\Exception;

class DiceShakerException extends DiceException
{
    const DICE_COLLECTION_MISSING = 1;
    const DICE_COLLECTION_NOT_NUMERIC = 2;
}
