<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once '../../jwt/src/BeforeValidException.php';
require_once '../../jwt/src/ExpiredException.php';
require_once '../../jwt/src/SignatureInvalidException.php';
require_once '../../jwt/src/JWT.php';

use \FIrebase\JWT\JWT;

$key = "SUperSecretKey";


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

if(isset($data->password))
{
    $password = $data->password;

}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'password'));
    http_response_code(400);

    exit();  
}




// if one of the inputs is empty 
if (empty($username) || empty($password))
{


   echo json_encode(array('error' => 'empty variables'));
   http_response_code(400);

   exit();

}

$sql = "SELECT id, id_random, username, password,  user_type FROM users WHERE username = ?;";
if($stmt = mysqli_prepare($connection, $sql))
{

    

    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $username);
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt))
    {

        // store results
        $results = mysqli_stmt_store_result($stmt);
        // Check if username exists, if yes then verify password
        if(mysqli_stmt_num_rows($stmt) < 1)
        {
            
            //, 'token'=>'1234=8oy4252njk'
            echo json_encode(array('error' => 'login fail'));
            http_response_code(400);
            exit();

        }

        mysqli_stmt_bind_result($stmt, $id, $idRandom, $username, $hashedPassword, $userType);


        if(mysqli_stmt_fetch($stmt))
        {
            
            if(password_verify($password, $hashedPassword))
            {
                $token = array(
                    "id" => $id,
                    "username" => $username,
                 );
                 $jwt = JWT::encode($token, $key);
                
                http_response_code(200);
                echo json_encode(array('login' => 'login success', 'token'=>$jwt, 'id'=>$id));
                exit();

            }
            else
            {
                echo json_encode(array('error' => 'invalid password'));
                http_response_code(400);
                exit();   
            }


        }

    }
    else
    {
        // echo json_encode(array('errconnectionor' => 'username taken'));
        http_response_code(500);
        exit();        

    }




}



// echo json_encode(array('errconnectionor' => 'username taken'));
http_response_code(500);
exit();   