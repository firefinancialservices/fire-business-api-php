<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
set_include_path(".:/usr/share/php:/home/owen/git/fire-business-api-php");
include_once("Fire/Starter.php");

// if using composer, do it the standard way
//require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');

$postdata = "eyJraWQiOiIwMDNlOTdlZS1hYzM4LTQwYmMtYjA5Ny0zZjA3NDU0MjU4ZGQiLCJhbGciOiJIUzI1NiJ9.eyJ0eG5JZCI6ODA5OSwicmVmSWQiOjEyMywidHlwZSI6IkxPREdFTUVOVCIsImZyb20iOnsidHlwZSI6IldJVEhEUkFXQUxfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjE1MDI0LCJhbGlhcyI6Ik15IEFJQiBhY2NvdW50IiwibnNjIjoiMTIzNDU2IiwiYWNjb3VudE51bWJlciI6IjEyMzQ1Njc4IiwiYmljIjoiQUlCSzFYWFgiLCJpYmFuIjoiSUUyOUFJQks5MzExNTIxMjM0NTY1NCJ9fSwidG8iOnsidHlwZSI6IkZJUkVfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjIwMDEsImFsaWFzIjoiRmlyZSBtYWluIGFjY291bnQiLCJuc2MiOiI5OTExOTkiLCJhY2NvdW50TnVtYmVyIjoiMDAwMDAwMDIiLCJiaWMiOiJDUEFZSUUyRCIsImliYW4iOiJJRTMzQ1BBWTk5MTE5OTAwMDAwMDAyIn19LCJjdXJyZW5jeSI6eyJjb2RlIjoiRVVSIiwiZGVzY3JpcHRpb24iOiJFdXJvIn0sImFtb3VudEJlZm9yZUNoYXJnZXMiOjYwMDAsImZlZUFtb3VudCI6NDksInRheEFtb3VudCI6MCwiYW1vdW50QWZ0ZXJDaGFyZ2VzIjo1OTUxLCJiYWxhbmNlIjo1OTUxLCJteVJlZiI6Ik15IGZpcnN0IGxvZGdlbWVudCIsImRhdGUiOiIyMDE5LTAzLTIyVDEwOjUyOjIzLjkxNFoiLCJmZWVEZXRhaWxzIjpbeyJmZWVSdWxlSWQiOjUwMDIsImFtb3VudENoYXJnZWQiOjQ5LCJmaXhlZEFtb3VudCI6NDl9XX0.RmFVpxTG6jGiD_ZnoeQnWqBqEOIoJcs4kzQX9qx-pqk";

$handler = new Fire\Business\Webhook\Handler($config['keyId'], $config['secret']);
$events = $handler->parse($postdata);

print_r ($events[0]);
print_r ($events[0]->get("amountBeforeCharges"));
print_r ($events[0]->get("to")->get("alias"));

?>
