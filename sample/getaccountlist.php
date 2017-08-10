<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
include_once("Fire/Starter.php");

// if using composer, do it the standard way
#require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');


# Set up the PHP Client and log in.
$client = new Fire\Business\Client();
$loggedInUser = $client->login($config['businessId'], $config['email'], $config['password'], $config['pindigits'], $config['totpseed']);

# Get lists of Fire Account and Payees
print_r ($client->accounts->read());
print_r ($client->payees->read());

#print_r ($client->payees->newPayee(array(
#	"accountName" => "Owen PHP", 
#	"accountHolderName" => "Owen O Byrne",
#	"currency" => "EUR", 
#	"iban" => "IE45XXXX12345678901234"
#)));

#print_r($client->payee(19729)->archive());

# Perform a bank transfer to a payee
print_r ($client->account(2150)->bankTransfer(array(
	"amount" => 1000,
       "currency" => "EUR",
        "payeeId" => 15996,
        "myRef" => "Testing",
        "theirRef" => "Testing BTs",
)));


# Perform an internal transfer between two Fire accounts
try {
        print_r ($client->account(2150)->fireTransfer(array(
                "amount" => 1000,
                "currency" => "EUR",
                "destinationAccountId" => 5532,
                "myRef" => "Testing PHP",
        )));
} catch (Exception $e) {
        print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
}


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
