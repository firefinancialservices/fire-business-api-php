# fire-business-api-php
A PHP library for accessing the Fire Business API

## Installation

You can install **fire-business-api-php** by downloading the latest [release][releases] or using composer:

`composer require fire/business-api-sdk`

To run the samples, edit the file `sample/config.inc.php.sample` and rename to `sample/config.inc.php`.

## Quickstart

### Decode a webhook

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

## Documentation

The documentation for the Fire Business API is available at [fire.com/docs][apidocs].

## Prerequisites

* PHP >= 5.3
* The PHP JSON extension
* The PHP cURL extension

# Getting help

If you need help installing or using the library, please contact Fire Support at support@fire.com.

If you've instead found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!

# Alpha API Deocumentation
This section of the docs concerns the Payment Initiation features of the API. These are only available using your business Id, email, password, PIN and Google Authenticator Code. This method of authentication will be replaced with stanfard API tokens and keys in 2017. Use this API with care!

## Authentication
```php
<?php 
// Set up the PHP Client and log in - keep your login credentials out of the code and out of Github!!
$client = new Fire\Business\Client();
$loggedInUser = $client->login($config['businessId'], $config['email'], $config['password'], $config['pindigits'], $config['totpseed']);
```

## Fire Accounts and Payees
```php
<?php
# Get lists of Fire Account and Payees
print_r ($client->accounts->read());
print_r ($client->payees->read());
``` 

## Details of Single Account/Payee
```php
<?php
# Get details of individual accounts/payees and the transactions for them.
print_r ($client->account(2150)->read());
print_r ($client->payee(15996)->read());
```

## Lists of transactions for an account or payee
```php
<?php
# Get details of individual accounts/payees and the transactions for them.
print_r ($client->account(2150)->transactions());
print_r ($client->payee(15996)->transactions());
```

## Internal transaction (same currency) between two Fire accounts
```php 
<?php
# Perform an internal transfer between two Fire accounts
print_r ($client->account(2150)->fireTransfer(array(
	"amount" => 1000,
        "currency" => "EUR",
        "destinationAccountId" => 5532,
        "myRef" => "Testing PHP",
)));
```

## Bank Transfer to a Payee
```php
<?php
# Perform a bank transfer to a payee
print_r ($client->account(2150)->bankTransfer(array(
	"amount" => 1000,
        "currency" => "EUR",
        "payeeId" => 15996,
        "myRef" => "Testing",
        "theirRef" => "Testing BTs",
)));
```




[releases]: https://github.com/firefinancialservices/fire-business-api-php/releases
[apidocs]: https://fire.com/docs
