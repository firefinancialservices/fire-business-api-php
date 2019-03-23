<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
//include_once("Fire/Starter.php");

// if using composer, do it the standard way
require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');


# Set up the PHP Client
$client = new Fire\Business\Client();
try {
	$client->initialise($config);
} catch (Exception $e) {
	print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
}

# Get lists of Fire Account and Payees
print_r ($client->accounts->read());
print_r ($client->payees->read());

# Get details of individual accounts/payees and the transactions for them.
print_r ($client->account(2150)->read());
print_r ($client->account(2150)->transactions(array("limit" => 2)));
print_r ($client->payee(15996)->read());
print_r ($client->payee(15996)->transactions());

# Get list of batches
print_r ($client->batches->read());

// Internal Transfers - create a batch, add/remove transfers, submit
$batch = $client->batches->create(array(
	"type" => "INTERNAL_TRANSFER",
  	"currency" => "EUR", 
  	"batchName" => "January 2018 Payroll",
  	"jobNumber" => "2018-01-PR",
  	"callbackUrl" => "https://requestbin.foursevensix.com/u0bnz1u0"
));
$batchId = $batch["batchUuid"];

print_r ($client->batch($batchId)->read());

$transaction = $client->batch($batchId)->addInternalTransfer(array(
	"icanFrom" => "2150",
  	"icanTo" => "5532", 
  	"amount" => "100",
  	"ref" => "Testing PHP Library"
));
print_r($transaction);

$internalTransfers = $client->batch($batchId)->internalTransfers->read();
print_r ($internalTransfers);
print_r ($client->batch($batchId)->internalTransfer($internalTransfers["items"][0]["batchItemUuid"])->delete());

$client->batch($batchId)->submit();


// Bank Transfers - create a batch, add/remove transfers, submit
$batch = $client->batches->create(array(
	"type" => "BANK_TRANSFER",
  	"currency" => "EUR", 
  	"batchName" => "March 2019 Payroll",
  	"jobNumber" => "2019-03-PR",
  	"callbackUrl" => "https://requestbin.foursevensix.com/u0bnz1u0"
));
$batchId = $batch["batchUuid"];

print_r ($client->batch($batchId)->read());

$transaction = $client->batch($batchId)->addBankTransfer(array(
	"icanFrom" => "2150",
	"payeeId" => "1304", 
	"payeeType" => "PAYEE_ID", 
	"amount" => "500",
	"myRef" => "PHP Library Test",
	"yourRef" => "PHP Library Test"
));
print_r($transaction);

$bankTransfers = $client->batch($batchId)->bankTransfers->read();
print_r ($bankTransfers);
print_r ($client->batch($batchId)->bankTransfer($bankTransfers["items"][0]["batchItemUuid"])->delete());

$client->batch($batchId)->submit();


