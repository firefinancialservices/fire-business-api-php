<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
//include_once("Fire/Starter.php");

// if using composer, do it the standard way
require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');

use Fire\Business\JWT;

$options = getopt("", array("ref::", "amount::", "currency::", "fromAccountNum::", "fromNsc::", "fromBIC::", "fromIBAN::", "toAccountNum::", "toIBAN::", "endpoint:"));

$originalJWT = "eyJraWQiOiJjZTkyNzFhNS1hZjRjLTRkYWYtYTFjZC1kM2I3YTY0MmQ5ZjgiLCJhbGciOiJIUzI1NiJ9.eyJ0eG5JZCI6ODA5OSwicmVmSWQiOjEyMywidHlwZSI6IkxPREdFTUVOVCIsImZyb20iOnsidHlwZSI6IldJVEhEUkFXQUxfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjE1MDI0LCJhbGlhcyI6Ik15IEFJQiBhY2NvdW50IiwibnNjIjoiMTIzNDU2IiwiYWNjb3VudE51bWJlciI6IjEyMzQ1Njc4IiwiYmljIjoiQUlCSzFYWFgiLCJpYmFuIjoiSUUyOUFJQks5MzExNTIxMjM0NTY1NCJ9fSwidG8iOnsidHlwZSI6IkZJUkVfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjIwMDEsImFsaWFzIjoiRmlyZSBtYWluIGFjY291bnQiLCJuc2MiOiI5OTExOTkiLCJhY2NvdW50TnVtYmVyIjoiMDAwMDAwMDIiLCJiaWMiOiJDUEFZSUUyRCIsImliYW4iOiJJRTMzQ1BBWTk5MTE5OTAwMDAwMDAyIn19LCJjdXJyZW5jeSI6eyJjb2RlIjoiRVVSIiwiZGVzY3JpcHRpb24iOiJFdXJvIn0sImFtb3VudEJlZm9yZUNoYXJnZXMiOjYwMDAsImZlZUFtb3VudCI6NDksInRheEFtb3VudCI6MCwiYW1vdW50QWZ0ZXJDaGFyZ2VzIjo1OTUxLCJiYWxhbmNlIjo1OTUxLCJteVJlZiI6Ik15IGZpcnN0IGxvZGdlbWVudCIsImRhdGUiOiIyMDE3LTEwLTAyVDExOjQ0OjIzLjE5MloiLCJmZWVEZXRhaWxzIjpbeyJmZWVSdWxlSWQiOjUwMDIsImFtb3VudENoYXJnZWQiOjQ5LCJmaXhlZEFtb3VudCI6NDl9XX0.Y_hz6VRsMZkABEbcXMH4RPEwX9rW2y9Su1kSxXsI9o4";

$decodedJWT = JWT::decode($originalJWT, $config['secret'], false);

if (!$options["endpoint"]) {
	print "Usage: php testwebhook.php --endpoint=https://example.com [options]\n";
	print "\n";
	print "  --ref=<reference> - the reference to use on the webhook lodgement\n";
	print "  --amount=<amount> - the amount (in pence/cent) to use on the lodgement\n";
	print "  --currency=<EUR|GBP> - the currency of the lodgement\n";
	print "  --fromAccountNum=<accountnum> - the account number this lodgement is from\n";
	print "  --fromNsc=<nsc> - the sortcode this lodgement is from\n";
	print "  --fromBIC=<bic> - the bic this lodgement is from\n";
	print "  --fromIBAN=<iban> - the IBAN this lodgement is from\n";
	print "  --toAccountNum=<accountnum> - the fire account number this lodgement is for\n";
	print "  --toIBAN=<iban> - the fire IBAN this lodgement is for\n";
	print "\n";
	print "\n";
	exit(0);
}

if ($options["ref"]) { 
	$decodedJWT->myRef = $options["ref"];
}

if ($options["currency"] == "GBP") { 
	$decodedJWT->currency->code = "GBP";
	$decodedJWT->currency->description = "Sterling";
}

if ($options["amount"]) { 
	$charges = $decodedJWT->feeAmount + $decodedJWT->taxAmount;
	$amountAfterCharges = $options["amount"] - $charges;
	$decodedJWT->amountBeforeCharges = $options["amount"];
	$decodedJWT->amountAfterCharges = $amountAfterCharges;
	$decodedJWT->balance = $decodedJWT->balance + $amountAfterCharges;
}

if ($options["fromAccountNum"]) { 
	$decodedJWT->from->account->accountNum = $options["fromAccountNum"];
}

if ($options["fromNsc"]) { 
	$decodedJWT->from->account->nsc = $options["fromNsc"];
}

if ($options["fromBIC"]) { 
	$decodedJWT->from->account->bic = $options["fromBIC"];
}

if ($options["fromIBAN"]) { 
	$decodedJWT->from->account->iban = $options["fromIBAN"];
}

if ($options["toAccountNum"]) { 
	$decodedJWT->to->account->accountNum = $options["toAccountNum"];
}

if ($options["toIBAN"]) { 
	$decodedJWT->to->account->iban = $options["toIBAN"];
}

$newJWT = JWT::encode($decodedJWT, $config["secret"]);


print_r($decodedJWT);
$ch = curl_init( $options["endpoint"] );
curl_setopt( $ch, CURLOPT_POST, 1);
curl_setopt( $ch, CURLOPT_POSTFIELDS, $newJWT);
curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt( $ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/jwt',
    'User-Agent: Fire-Webhook/1.0'
));
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch )  or die(curl_error($ch)); ;

?>
