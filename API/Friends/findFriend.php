<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData);


include_once '../../Config/dbc.php';


if(isset($data->username))
{
    $username = $data->username;
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'username'));
    http_response_code(400);
    exit();   
}
if(isset($data->token))
{
    $token = $data->token;
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'token'));
    http_response_code(400);
    exit();   

    //////
    //if token is not right 
    //
    echo json_encode(array('error' => 'invalid token'));
    http_response_code(400);
    exit();   

}


$sql = "SELECT id, id_random, username FROM users WHERE username = ?;";



if($stmt = mysqli_prepare($connection, $sql))
{
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $username);

    if(mysqli_stmt_execute($stmt))
    {
        // store results
        $results = mysqli_stmt_store_result($stmt);
        // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) < 1)
        {
            
            //, 'token'=>'1234=8oy4252njk'
            echo json_encode(array('error' => 'no user with  '));
            http_response_code(400);
            exit();

        }

        mysqli_stmt_bind_result($stmt, $id, $idRandom, $username);
    

        

    }

}



http_response_code(500);
exit();   
