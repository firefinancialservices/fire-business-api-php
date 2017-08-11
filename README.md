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
* The PHP MBString extension
* The PHP cURL extension

# Getting help

If you need help installing or using the library, please contact Fire Support at support@fire.com.

If you've instead found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!

# Alpha API Documentation
This section of the docs concerns the Payment Initiation features of the API. These are only available using your business Id, email, password, PIN and Google Authenticator Code. This method of authentication will be replaced with standard API tokens and keys in 2017. Use this API with care!

## Authentication
If there are issues signing in, a ```RestException``` will be thrown. Inspect ```$e->getCode()``` and ```$e->getMessage()``` for more details.

|Error Code|Message|Description|
|-|-|-|
|50013|The login attempt was not successful due to bad credentials|Check your login details - businessId, email and password.|
|50404|Generic message for all other errors|We hide the details of a lot of errors for security purposes. This can make it difficult to find the root cause of a problem|

```php
<?php 
// Set up the PHP Client and log in - keep your login credentials out of the code and out of Github!!
$client = new Fire\Business\Client();
$loggedInUser = $client->login($config['businessId'], $config['email'], $config['password'], $config['pindigits'], $config['totpseed']);
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

## Internal transaction (same currency) between two Fire accounts
### Input Array Details
|Field Name|Description|
|-|-|
|amount|The amount to transfer (in cent or pence)|
|currency|The currency of the transfer (EUR or GBP)|
|destinationAccountId|The accountId of the account to transfer to. Must be an account in your Fire profile, in the same currency as the originating account|
|myRef|A message to put on the transaction - same message on both accounts. Max 50 chars.|
### Response Details
|Field Name|Description|
|-|-|
|refId|The id of the transaction|

If there are issues with the transfer, a ```RestException``` will be thrown. Inspect ```$e->getCode()``` and ```$e->getMessage()``` for more details.

|Error Code|Message|Description|
|-|-|-|
|50402|Insufficient Funds|Not enough money in the account to cover the transfer.|
|50416|The account does not accept that currency|Did you use GBP when you should have used EUR?|
|50404|Generic message for all other errors|We hide the details of a lot of errors for security purposes. This can make it difficult to find the root cause of a problem|



```php 
<?php
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

```

## Add a new Payee
### Input Array Details
|Field Name|Description|
|-|-|
|accountName|The name/alias to give the payer - doesn't need to be the account holder name.|
|currency|The currency of the payer account (EUR or GBP)|
|accountHolderName|The account holder name as given to you by the payee. If this doesn't match the real payee name, payments to this payee may get returned by the destination bank.|
|iban|For EUR payees, use the IBAN.|
|nsc|For GBP payees, use the Sort Code (NSC) and Account Number. |
|accountNumber|For GBP payees, use the Sort Code (NSC) and Account Number.|

### Response Details
An HTTP 204 response with no body will be returned.  

If there are issues with the transfer, a ```RestException``` will be thrown. Inspect ```$e->getCode()``` and ```$e->getMessage()``` for more details.

|Error Code|Message|Description|
|-|-|-|
|50505|The payee you are trying to add is already live.|You've already got a Payee with those account details.|
|50511|The IBAN entered is an invalid IBAN. Please enter a valid IBAN.|Check the IBAN to make sure it is correct|
|50508|The sort code entered is an invalid sort code. Please enter a valid sort code.|Check your sort code|
|50509|The account number entered is an invalid account number. Please enter a valid account number.|Check your account number|
|50516|The payee name already exists. Please input a unique name|You've already used that ```accountName```|
|50100|The current Access Code you have entered is incorrect|Make sure you are using the right Access Code PIN digits.|
|50122|Incorrect authenticator token. Please try again.|Make sure the TOTP secret is correct, and that the time on the server is correct|
|50404|Sorry, we are unable to proceed with your request.|Generic message for all other errors. We hide the details of a lot of errors for security purposes. This can make it difficult to find the root cause of a problem|

```php 
<?php
# Add a new Payee (EUR)
try {
    print_r ($client->payees->newPayee(array(
    	"accountName" => "A name for the Account",
        "accountHolderName" => "John Doe"
    	"currency" => "EUR",
        "iban" => "IE12AIBK12345612345678",
    )));
    
} catch (Exception $e) {
        print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
        
}

# Add a new Payee (GBP)
try {
    print_r ($client->payees->newPayee(array(
    	"accountName" => "A name for the Account",
        "accountHolderName" => "John Doe"
    	"currency" => "GBP",
        "nsc" => "123456",
    	"accountNumber" => "12345678",
    )));
    
} catch (Exception $e) {
        print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
        
}
```

## Archive a payee
```php
<?php
# Archive a payee
try {
    print_r ($client->payee(15996)->archive());
    
} catch (Exception $e) {
        print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
        
}
```

## Bank Transfer to a Payee
### Input Array Details
|Field Name|Description|
|-|-|
|amount|The amount of the transfer in cent or pence.|
|currency|The currency of the payer account (EUR or GBP)|
|payeeId|The ID of the payee. |
|myRef|This is the narrative/reference that will be shown on your Fire account for this transaction.|
|theirRef|This is the narrative/reference that will be shown to the payee.  |

### Response Details
|Field Name|Description|
|-|-|
|refId|The id of the transaction|

If there are issues with the transfer, a ```RestException``` will be thrown. Inspect ```$e->getCode()``` and ```$e->getMessage()``` for more details.

|Error Code|Message|Description|
|-|-|-|
|400|Invalid value|One of your input fields has bad data.|
|50402|Insufficient Funds|Not enough money in the account to cover the transfer.|
|50100|The current Access Code you have entered is incorrect|Make sure you are using the right Access Code PIN digits.|
|50404,50514|Sorry, we are unable to proceed with your request.|Generic message for all other errors. We hide the details of a lot of errors for security purposes. This can make it difficult to find the root cause of a problem|

```php
<?php
# Perform a bank transfer to a payee
try {
    print_r ($client->account(2150)->bankTransfer(array(
    	"amount" => 1000,
        "currency" => "EUR",
        "payeeId" => 15996,
        "myRef" => "Testing",
        "theirRef" => "Testing BTs",
    )));
        
} catch (Exception $e) {
        print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
        
}
```

## Add a new Fire Account
### Input Array Details
|Field Name|Description|
|-|-|
|accountName|The name of the new account. This name should be used as the account name on all incoming transfers to pass Fire lodgement monitoring.|
|currency|The currency of the transfer (EUR or GBP)|
### Response Details
An HTTP 204 response with no body will be returned.  

If there are issues adding the new account, a ```RestException``` will be thrown. Inspect ```$e->getCode()``` and ```$e->getMessage()``` for more details.

|Error Code|Message|Description|
|-|-|-|
|50402|Insufficient Funds|Not enough money in your default account to cover the cost of the new account.|
|50410|You have reached the maximum number of accounts for this currency.|You have a limit of new accounts you can add - check the limits page in Settings.|
|50419|The account name already exists. Please input a unique name.|You've already used that ```accountName```.|
|50404|Generic message for all other errors|We hide the details of a lot of errors for security purposes. This can make it difficult to find the root cause of a problem|

```php
<?php
# Add a new account (this request incurs a fee!)
try {
    print_r ($client->accounts->newAccount(array(
           "accountName" => "Testing PHP GBP",
           "currency" => "GBP",    
    )));
    
} catch (Exception $e) {
    print_r ($e->getCode() . ': ' . $e->getMessage() . "\n");
        
}
```

[releases]: https://github.com/firefinancialservices/fire-business-api-php/releases
[apidocs]: https://fire.com/docs
