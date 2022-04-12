<?php
//need auth 

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


$rawData = file_get_contents("php://input");
$data = json_decode($rawData);

include_once '../../Config/dbc.php';


if(isset($data->id))
{
    $username = $data->username;

     
}


