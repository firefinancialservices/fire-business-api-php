# fire-business-api-php
A PHP library for accessing the Fire Business API

[![Latest Version](https://img.shields.io/packagist/v/fire/business-api-sdk.svg?style=flat-square)](https://packagist.org/packages/fire/business-api-sdk) [![License](https://img.shields.io/packagist/l/fire/business-api-sdk.svg?style=flat-square)](LICENSE) 


## Installation

You can install **fire-business-api-php** by downloading the latest [release][releases] or using composer:

`composer require fire/business-api-sdk`

To run the samples, edit the file `sample/config.inc.php.sample` and rename to `sample/config.inc.php`.

## Documentation

The documentation for the Fire Business API is available at [fire.com/docs][apidocs].

## Prerequisites

* PHP >= 5.3
* The PHP JSON extension
* The PHP MBString extension
* The PHP cURL extension

# Getting help

If you need help installing or using the library, please contact Fire Support at support@fire.com.

If you've instead found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!

## Authentication
If there are issues setting up a session, a ```RestException``` will be thrown. Inspect ```$e->getCode()``` and ```$e->getMessage()``` for more details.

|Error Code|Message|Description|
|-|-|-|
|50404|Sorry, we are unable to proceed with your request.|Generic message for all other errors. We hide the details of a lot of errors for security purposes. This can make it difficult to find the root cause of a problem|

```php
<?php 
// Set up the PHP Client and log in - keep your login credentials out of the code and out of Github!!
$client = new Fire\Business\Client();
$client->initialise($config);
```

To use a different endpoint instead of the live API, pass it in the ```$client``` constructor as follows:
```php
<?php
$client = new Fire\Business\Client("https://api.fire.com/something");
```

## Fire Accounts and Payees
See [fire.com/docs][apidocs] for details of the objects returned.
```php
<?php
# Get lists of Fire Account and Payees
print_r ($client->accounts->read());
print_r ($client->payees->read());
``` 

## Details of Single Account/Payee
See [fire.com/docs][apidocs] for details of the objects returned.
```php
<?php
# Get details of individual accounts/payees and the transactions for them.
print_r ($client->account(2150)->read());
print_r ($client->payee(15996)->read());
```

## Lists of transactions for an account or payee
See [fire.com/docs][apidocs] for details of the objects returned.
```php
<?php
# Get details of individual accounts/payees and the transactions for them.
print_r ($client->account(2150)->transactions());
print_r ($client->payee(15996)->transactions());
```

This returns 10 transactions by default. Use an ```$options``` array to page as follows:
```php
<?php
# Get items 10-35 of a list of transactions
print_r ($client->account(2150)->transactions(array("offset"=>10, "limit"=>25)));
```

## Internal transaction (same currency) between two Fire accounts
The fire.com API allows businesses to make payments between their accounts automatically.

The process is as follows:
1. Create a new batch
1. Add transfers to the batch
1. Submit the batch for processing.

Transfers are performed in batches of up to 100 items. Internal transfers don't require approval. 

If there are issues with the transfer, a ```RestException``` will be thrown. Inspect ```$e->getCode()``` and ```$e->getMessage()``` for more details.

|Error Code|Message|Description|
|-|-|-|
|50402|Insufficient Funds|Not enough money in the account to cover the transfer.|
|50416|The account does not accept that currency|Did you use GBP when you should have used EUR?|
|50404|Sorry, we are unable to proceed with your request.|Generic message for all other errors. We hide the details of a lot of errors for security purposes. This can make it difficult to find the root cause of a problem|

```php 
<?php
# Perform an internal transfer between two of your Fire accounts
try {
	$batch = $client->batches->create(array(
		"type" => "INTERNAL_TRANSFER",
		"currency" => "EUR",
		"batchName" => "January 2018 Payroll",
		"jobNumber" => "2018-01-PR",
		"callbackUrl" => "https://myserver.com/callback"
	));
	$batchId = $batch["batchUuid"];

	# retrieve batch details
	print_r ($client->batch($batchId)->read());

	# Add an internal transfer
	$transaction = $client->batch($batchId)->addInternalTransfer(array(
		"icanFrom" => "2150",
		"icanTo" => "5532",
		"amount" => "100",
		"ref" => "Testing PHP Library"
	));
	print_r($transaction);

	# List trasnfers in the batch
	$internalTransfers = $client->batch($batchId)->internalTransfers->read();
	print_r ($internalTransfers);
	
	# remove a transfer if required.
	print_r ($client->batch($batchId)->internalTransfer($internalTransfers["items"][0]["batchItemUuid"])->delete());

	# Submit the batch - this executes the transfers immediately.
	$client->batch($batchId)->submit();
        
} catch (Exception $e) {
        print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
        
}

```

## Bank Transfer to a Payee
The fire.com API allows businesses to automate payments to third parties across the UK and Europe.

For security, the API can only set up the payments. The batches of payments must be approved by an authorised user via the firework mobile app.

The process is as follows:
1. Create a new batch
1. Add payments to the batch
1. Submit the batch for approval.

Once the batch is submitted, the authorised users will receive notifications to their firework mobile apps. They can review the contents of the batch and then approve or reject it. If approved, the batch is then processed.

There are two ways to process bank transfers - by Payee ID (Mode 1) or by Payee Account Details (Mode 2).

|Mode|Description|
|---|---|
|Mode 1|Set the `payeeType` to `PAYEE_ID` to use the payee IDs of existing approved payees set up against your account. These batches can be approved in the normal manner.|
|Mode 2|Set the `payeeType` to `ACCOUNT_DETAILS` to use the account details of the payee. In the event that these details correspond to an existing approved payee, the batch can be approved as normal. If the account details are new, a batch of New Payees will automatically be created. This batch will need to be approved before the Payment batch can be approved. These payees will then exist as approved payees for future batches.|

If there are issues with the transfer, a ```RestException``` will be thrown. Inspect ```$e->getCode()``` and ```$e->getMessage()``` for more details.

|Error Code|Message|Description|
|-|-|-|
|50402|Insufficient Funds|Not enough money in the account to cover the transfer.|
|50404,50514|Sorry, we are unable to proceed with your request.|Generic message for all other errors. We hide the details of a lot of errors for security purposes. This can make it difficult to find the root cause of a problem|

```php
<?php
# Perform a bank transfer to a payee
try {
        // Bank Transfers - create a batch, add/remove transfers, submit
	$batch = $client->batches->create(array(
		"type" => "BANK_TRANSFER",
		"currency" => "EUR",
		"batchName" => "March 2019 Payroll",
		"jobNumber" => "2019-03-PR",
		"callbackUrl" => "https://myserver.com/callback"
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
	
	# Submit the batch. This triggers a notification to be sent to the Firework for Business mobile app for approval.
	# Transfers will not be processed until the batch is approved.
	$client->batch($batchId)->submit();
	
	# If required, the batch can be cancelled up until it is approved.
	$client->batch($batchId)->cancel();
 
} catch (Exception $e) {
        print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
        
}
```

# Decode a webhook

```php
<?php
// you can either pull in the library using Fire/Starter if you've copied the library to your standard library location.
//include_once("Fire/Starter.php");

// Or using the Composer dependency manager
require "vendor/autoload.php"; 

// Receive a webhook from your business account
$keyId = "XXXXXX"; // The Key ID associated with your webhook
$secret = "YYYYYY"; // The Secret associated with your webhook

$handler = new Fire\Business\Webhook\Handler($keyId, $secret);

// $events is an array of Fire\Business\Model\Transactions
$events = $handler->parse($raw_post_data);

print $events[0];
```

### Send a test webhook
You can send a webhook to your server using the sample script in samples/.

```php testwebhook.php --ref="INV 23798" --amount=1249 --endpoint=https://2i7yqo19qv39.runscope.net/``` 

The options are:
```
Usage: php testwebhook.php --endpoint=https://example.com [options]

  --ref=<reference> - the reference to use on the webhook lodgement
  --amount=<amount> - the amount (in pence/cent) to use on the lodgement
  --currency=<EUR|GBP> - the currency of the lodgement
  --fromAccountNum=<accountnum> - the account number this lodgement is from
  --fromNsc=<nsc> - the sortcode this lodgement is from
  --fromBIC=<bic> - the bic this lodgement is from
  --fromIBAN=<iban> - the IBAN this lodgement is from
  --toAccountNum=<accountnum> - the fire account number this lodgement is for
  --toIBAN=<iban> - the fire IBAN this lodgement is for
```


[releases]: https://github.com/firefinancialservices/fire-business-api-php/releases
[apidocs]: https://fire.com/docs
