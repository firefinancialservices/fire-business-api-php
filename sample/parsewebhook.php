<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
include_once("Fire/Starter.php");

// if using composer, do it the standard way
//require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');

$postdata = "eyJraWQiOiIwMDNlOTdlZS1hYzM4LTQwYmMtYjA5Ny0zZjA3NDU0MjU4ZGQiLCJhbGciOiJIUzI1NiJ9.eyJ0eG5JZCI6ODA5OSwicmVmSWQiOjEyMywidHlwZSI6IkxPREdFTUVOVCIsImZyb20iOnsidHlwZSI6IldJVEhEUkFXQUxfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjE1MDI0LCJhbGlhcyI6Ik15IEFJQiBhY2NvdW50IiwibnNjIjoiMTIzNDU2IiwiYWNjb3VudE51bWJlciI6IjEyMzQ1Njc4IiwiYmljIjoiQUlCSzFYWFgiLCJpYmFuIjoiSUUyOUFJQks5MzExNTIxMjM0NTY1NCJ9fSwidG8iOnsidHlwZSI6IkZJUkVfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjIwMDEsImFsaWFzIjoiRmlyZSBtYWluIGFjY291bnQiLCJuc2MiOiI5OTExOTkiLCJhY2NvdW50TnVtYmVyIjoiMDAwMDAwMDIiLCJiaWMiOiJDUEFZSUUyRCIsImliYW4iOiJJRTMzQ1BBWTk5MTE5OTAwMDAwMDAyIn19LCJjdXJyZW5jeSI6eyJjb2RlIjoiRVVSIiwiZGVzY3JpcHRpb24iOiJFdXJvIn0sImFtb3VudEJlZm9yZUNoYXJnZXMiOjYwMDAsImZlZUFtb3VudCI6MSwidGF4QW1vdW50IjoyLCJhbW91bnRBZnRlckNoYXJnZXMiOjU5OTcsImJhbGFuY2UiOjU5OTcsIm15UmVmIjoiTXkgZmlyc3QgbG9kZ2VtZW50IiwiZGF0ZSI6IjIwMTgtMDUtMjJUMTM6NDc6NTcuNjgxWiIsImZlZURldGFpbHMiOlt7ImZlZVJ1bGVJZCI6NTAwMiwiYW1vdW50Q2hhcmdlZCI6MSwiZml4ZWRBbW91bnQiOjF9XX0.CxwdYYbZ926SYObxEi6gm1pxCi5CRtyStwlXee87IpA"; 

$handler = new Fire\Business\Webhook\Handler($config['keyId'], $config['secret']);
$events = $handler->parse($postdata);

print $events[0];
print $events[0]->get("amountBeforeCharges");
print $events[0]->get("to")->get("alias");

?>
