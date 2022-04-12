<?php
$serverName = "localhost";
$dBUsername =" asavu";
$dBPassword = "5iSimEj";
$dBName = "asavu";

//$conn=mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);


$connection=mysqli_connect("localhost", "Chat", "Parola123", "Chat");


if(mysqli_connect_errno())
{
    echo "Error: could not connect to database: " .mysqli_connect_error();


}
