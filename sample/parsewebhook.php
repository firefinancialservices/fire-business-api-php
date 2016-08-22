<?php

// If you aren't using composer, include the Fire/Starter file to grab all the Fire libraries
include_once("Fire/Starter.php");

// if using composer, do it the standard way
//require_once "vendor/autoload.php";

// Configs from another file
include_once('config.inc.php');

$postdata = "eyJraWQiOiIwMDNlOTdlZS1hYzM4LTQwYmMtYjA5Ny0zZjA3NDU0MjU4ZGQiLCJhbGciOiJIUzI1NiJ9.eyJ0eG5JZCI6MzYwNTQsInJlZklkIjozMDgyOSwidHhuVHlwZSI6eyJ0eXBlIjoiTE9ER0VNRU5UIiwiZGVzY3JpcHRpb24iOiJMb2RnZW1lbnQifSwiZnJvbSI6eyJ0eXBlIjoiRVhURVJOQUxfQUNDT1VOVCIsImFjY291bnQiOnsiaWQiOjgzMCwiYWxpYXMiOiJTVFJJUEUifX0sInRvIjp7InR5cGUiOiJGSVJFX0FDQ09VTlQiLCJhY2NvdW50Ijp7ImlkIjoyMTUwLCJhbGlhcyI6Ik1haW4gQWNjb3VudCIsIm5zYyI6Ijk5MTE5OSIsImFjY291bnROdW1iZXIiOiI3NzkwMDc5NCIsImJpYyI6IkNQQVlJRTJEIiwiaWJhbiI6IklFNDRDUEFZOTkxMTk5Nzc5MDA3OTQifX0sImN1cnJlbmN5Ijp7ImNvZGUiOiJFVVIiLCJkZXNjcmlwdGlvbiI6IkV1cm8ifSwiYW1vdW50QmVmb3JlRmVlIjozNTgsImZlZUFtb3VudCI6MywiYW1vdW50QWZ0ZXJGZWUiOjM1NSwiYmFsYW5jZSI6NDg1ODYsIm15UmVmIjoiTk9UUFJPVklERUQiLCJkYXRlIjoxNDU2NDA4Mzg5OTkwLCJmZWVEZXRhaWxzIjpbeyJhbW91bnRDaGFyZ2VkIjozLCJtaW5pbXVtQW1vdW50IjoxLCJtYXhpbXVtQW1vdW50Ijo0OSwiZml4ZWRQZXJjZW50YWdlNGQiOjEwMDAwfV19.CpG3D-E5QMYg9XDmnE9S_3O89Ia59wdFvFCPYKWB4gg";

$handler = new Fire\Business\Webhook\Handler($config['keyId'], $config['secret']);
$events = $handler->parse($postdata);

print $events[0];
print $events[0]->get("amountBeforeFee");

?>
