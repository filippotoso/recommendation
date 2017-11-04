<?php

require_once(__DIR__ . '/src/Util.php');
require_once(__DIR__ . '/src/Engine.php');
require_once(__DIR__ . '/src/SimpleEngine.php');
require_once(__DIR__ . '/src/BinaryEngine.php');

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

// Display recommendations
print_r($result);

// Get all the recommendations for all the users
$result = $engine->recommendations();

// Display recommendations
print_r($result);
