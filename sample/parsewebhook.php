<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
include_once("Fire/Starter.php");

// if using composer, do it the standard way
//require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');

$postdata = "eyJraWQiOiJjZTkyNzFhNS1hZjRjLTRkYWYtYTFjZC1kM2I3YTY0MmQ5ZjgiLCJhbGciOiJIUzI1NiJ9.eyJ0eG5JZCI6ODA5OSwicmVmSWQiOjEyMywidHlwZSI6IkxPREdFTUVOVCIsImZyb20iOnsidHlwZSI6IldJVEhEUkFXQUxfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjE1MDI0LCJhbGlhcyI6Ik15IEFJQiBhY2NvdW50IiwibnNjIjoiMTIzNDU2IiwiYWNjb3VudE51bWJlciI6IjEyMzQ1Njc4IiwiYmljIjoiQUlCSzFYWFgiLCJpYmFuIjoiSUUyOUFJQks5MzExNTIxMjM0NTY1NCJ9fSwidG8iOnsidHlwZSI6IkZJUkVfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjIwMDEsImFsaWFzIjoiRmlyZSBtYWluIGFjY291bnQiLCJuc2MiOiI5OTExOTkiLCJhY2NvdW50TnVtYmVyIjoiMDAwMDAwMDIiLCJiaWMiOiJDUEFZSUUyRCIsImliYW4iOiJJRTMzQ1BBWTk5MTE5OTAwMDAwMDAyIn19LCJjdXJyZW5jeSI6eyJjb2RlIjoiRVVSIiwiZGVzY3JpcHRpb24iOiJFdXJvIn0sImFtb3VudEJlZm9yZUNoYXJnZXMiOjYwMDAsImZlZUFtb3VudCI6NDksInRheEFtb3VudCI6MCwiYW1vdW50QWZ0ZXJDaGFyZ2VzIjo1OTUxLCJiYWxhbmNlIjo1OTUxLCJteVJlZiI6Ik15IGZpcnN0IGxvZGdlbWVudCIsImRhdGUiOiIyMDE3LTEwLTAyVDExOjQ0OjIzLjE5MloiLCJmZWVEZXRhaWxzIjpbeyJmZWVSdWxlSWQiOjUwMDIsImFtb3VudENoYXJnZWQiOjQ5LCJmaXhlZEFtb3VudCI6NDl9XX0.Y_hz6VRsMZkABEbcXMH4RPEwX9rW2y9Su1kSxXsI9o4";

$handler = new Fire\Business\Webhook\Handler($config['keyId'], $config['secret']);
$events = $handler->parse($postdata);

print $events[0];
print $events[0]->get("amountBeforeCharges");
print $events[0]->get("to")->get("alias");

?>
