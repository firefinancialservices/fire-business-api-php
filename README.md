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

The documentation for the Fire Business API is available at [paywithfire.com/docs][apidocs].

## Prerequisites

* PHP >= 5.3
* The PHP JSON extension

# Getting help

If you need help installing or using the library, please contact Fire Support at support@paywithfire.com.

If you've instead found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!

[releases]: https://github.com/firefinancialservices/fire-business-api-php/releases
[apidocs]: https://paywithfire.com/docs
