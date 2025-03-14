<?php

spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});


$strongEnemy = new StrongEnemy();
$weakEnemy = new WeakEnemy();

echo $strongEnemy->attack();

echo $weakEnemy->attack();

