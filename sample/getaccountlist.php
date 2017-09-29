<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
//include_once("Fire/Starter.php");

// if using composer, do it the standard way
require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');


# Set up the PHP Client and log in.
$client = new Fire\Business\Client();
try {
	$loggedInUser = $client->login($config['businessId'], $config['email'], $config['password'], $config['pindigits'], $config['totpseed']);
} catch (Exception $e) {
	print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
}

# Get lists of Fire Account and Payees
#print_r ($client->accounts->read());
#print_r ($client->payees->read());

#try { 
#	print_r ($client->payees->newPayee(array(
#		"accountName" => "Second Fire Personal", 
#		"accountHolderName" => "Owen O Byrne",
#		"currency" => "EUR", 
#		"iban" => "IE30CPAY99119998604532"
#	)));
#} catch (Exception $e) {
#	print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
#}

#print_r($client->payee(19729)->archive());

# Perform a bank transfer to a payee
try {
	print_r ($client->account(2150)->bankTransfer(array(
		"amount" => 100,
	       "currency" => "EUR",
		"payeeId" => 15996,
		"myRef" => "Testing",
		"theirRef" => "Testing BTs",
	)));
} catch (Exception $e) {
	print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
}


# Perform an internal transfer between two Fire accounts
#try {
#	print_r ($client->account(2150)->fireTransfer(array(
#		"amount" => 1000,
#		"currency" => "GBP",
#		"destinationAccountId" => 5532,
#		"myRef" => "Testing PHP",
#	)));
#} catch (Exception $e) {
#	print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
#}


# Get details of individual accounts/payees and the transactions for them.
#print_r ($client->account(2150)->read());
#print_r ($client->account(2150)->transactions());
#print_r ($client->payee(15996)->read());
#print_r ($client->payee(15996)->transactions());


#print_r ($client->accounts->newAccount(array(
#	"accountName" => "Testing PHP GBP",
#	"currency" => "GBP",	
#)));
	
?>
