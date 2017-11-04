<?php

/* Generate random preferences (simple and binary) */

$pizzas = include(__DIR__) . '/sources/pizzas.php';
$users =  include(__DIR__) . '/sources/users.php';

$result = [];

foreach ($users as $user) {
    shuffle($pizzas);
    $result[$user] = array_slice($pizzas, 0, 6);
}

file_put_contents(__DIR__ . '/simple_preferences.php', '<' . '?php return ' . var_export($result, TRUE) . '; ?' . '>');

$result = [];

foreach ($users as $user) {
    shuffle($pizzas);
    $result[$user]['like'] = array_slice($pizzas, 0, 6);
    $result[$user]['dislike'] = array_slice($pizzas, 6, 3);
}

file_put_contents(__DIR__ . '/binary_preferences.php', '<' . '?php return ' . var_export($result, TRUE) . '; ?' . '>');
