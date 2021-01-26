<?php

require_once __DIR__ . '/vendor/autoload.php';

$bot = new WeeklyCheckKeys\WeeklyCheckBot();
var_dump($bot->onListening());