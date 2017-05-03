<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
include_once("Fire/Starter.php");

// if using composer, do it the standard way
//require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');

$client = new Fire\Business\Client($config['authorizationToken']);

print_r ($client->accounts->read());

?>
