<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

include 'Dispatcher.php';
include 'Autoloader.php';

use App\Menu\Autoloader;

Autoloader::register();


$dispatcher = new Dispatcher();
$dispatcher->dispatch();
