# dice-roller

## Dice

A single dice can be created and rolled multiple times.

### Creating a Dice

Create a standard numeric Dice by passing a single number into the constructor:
```php
// Create a d6 (Number range 1 - 6)
$d6 = new Dice(6);

// Create a d100 (Number range 1 - 100)
$d100 = new Dice(100);
```

Create a Dice with custom sides by passing an array of numbers or strings: 
```php
// Create a Dice with custom selection of numbers:
$evenDice = new Dice([2, 2, 4, 4, 6, 6]);

// Create a Dice of strings, such as colours:
$colourDice = new Dice(['Red', 'Blue' 'Green']);

// ...or shapes:
$shapeDice = new Dice(['Triangle', 'Square', 'Circle', 'Cross']);
```

### Rolling

You can roll a Dice and get the value that was rolled:
```php
$dice = new Dice(6);

// Separate calls:
$dice->roll();
$firstValue = $dice->getValue();

// ...or chained calls:
$secondValue = $dice->roll()->getValue();
```

### Custom Rollers

You can customise the random number generation by injecting an implementation of `RollerInterface`.

There are two preset rollers:
```php
// Create a d6 with the QuickRoller
// NOTE: QuickRoller is the default roller when one isn't specified
$dice = new Dice(6, new Rollers\QuickRoller);

// Create a d20 with the StrongRoller
// The StrongRoller could be used for cryptographical purposes
$dice = new Dice(20, new Rollers\StrongRoller);
```

### History

You can see the history rolls:
```php
$dice = new Dice(6);

// Enable the history (History is disabled by default)
$dice->enableHistory();

$dice->roll(5); // Roll the Dice 5 times

$values = $dice->getHistory(); // i.e. [3, 2, 5, 6, 2]

// History can be disabled with: $dice->disableHistory()

// History can be cleared with: $dice->clearHistory();
```