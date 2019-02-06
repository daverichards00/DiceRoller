# TODO

- Dice class to include constants to represent d4, d4, d8, d10, "tens" d10, d12, d20 & dF.
- New selectors: excludeLowest(x) and excludeHighest(x)
- New selector: notEqualTo
- New selector: notIn
- New selector: notThese
- New DiceShaker getter: getProductValue()
- Support for modifiers: addition / subtraction / multiplication / division:
  - The result of all the Dice in set. i.e. 5xd6
  - Individual Dice result within set. i.e. d6x10+d6
- Investigate better support for rolls such as: 
> 3 x (2d6+4) means "roll two 6-sided dice adding four to result, repeat the roll 3 times adding the results together."