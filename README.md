# Recommendation library

A simple recommendation library for small projects.
The SimpleEngine supports only the "liked" action (i.e. Philip likes the Caprese pizza).
The BinatyEngine supports both "liked" and "disliked" actions (i.e. Philip likes the Verdure pizza but dislikes the Arrabbiata pizza).

## Requirements

- PHP 5.6+

## Installing

Use Composer to install it:

```
composer require filippo-toso/recommendation
```

## Using It

```
use FilippoToso\Recommendation\SimpleEngine;
use FilippoToso\Recommendation\BinaryEngine;

/****************** Simple Engine *********************/

// Creating the recommendation engine
$engine = new SimpleEngine();

// Bulk loading the preferences
$preferences = include(__DIR__) . '/data/simple_preferences.php';
$engine->bulk_load($preferences);

// Returns recommendations only for new elements
$engine->option('new', TRUE);

// Returns recommendations sorted
$engine->option('sort', TRUE);

// Preparing the engine
$engine->prepare();

// How much will Philip like the Diavola pizza?
$result = $engine->liking('Philip', 'Diavola');
printf("Philip liking for the Diavola pizza: %f\r\n", $result);

// Get all the recommended pizzas for Philip
print("Philip recommendations: ");
$result = $engine->recommendations('Philip');

// Sort the result by liking
arsort($result);

// Display recommendations
print_r($result);

// Get all the recommendations for all the users
$result = $engine->recommendations();

// Display recommendations
print_r($result);

print("\r\n");

/****************** Binary Engine *********************/

// Creating the recommendation engine
$engine = new BinaryEngine();

// Bulk loading the preferences
$preferences = include(__DIR__) . '/data/binary_preferences.php';
$engine->bulk_load($preferences);

// Returns recommendations only for new elements
$engine->option('new', TRUE);

// Returns recommendations sorted
$engine->option('sort', TRUE);

// Preparing the engine
$engine->prepare();

// How much will Philip like the Diavola pizza?
$result = $engine->liking('Philip', 'Diavola');
printf("Philip liking for the Diavola pizza: %f\r\n", $result);

// Get all the recommended pizzas for Philip
print("Philip recommendations: ");
$result = $engine->recommendations('Philip');

// Sort the result by liking
arsort($result);

// Display recommendations
print_r($result);

// Get all the recommendations for all the users
$result = $engine->recommendations();

// Display recommendations
print_r($result);

```
