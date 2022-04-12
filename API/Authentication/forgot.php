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
    if(empty($username))
    {

        echo json_encode(array('error' => 'empty variables', 'variable missing' => 'username'));
        http_response_code(400);
        exit();
    }
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'username'));
    http_response_code(400);
    exit();   
}


if(isset($data->sendTo))
{
    $sendTo = $data->sendTo;
}

if(empty($sendTo))
{

}
 


$sql = "SELECT id, username, is_email, email, is_phone, phone, phone_prefix FROM users WHERE username = ?;";

if($stmt = mysqli_prepare($connection, $sql))
{

    

    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $username);
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt))
    {

        // store results
        $results = mysqli_stmt_store_result($stmt);
        
        // Check if username exists
        if(mysqli_stmt_num_rows($stmt) < 1)
        {
            
            //, 'token'=>'1234=8oy4252njk'
            echo json_encode(array('error' => 'no user'));
            http_response_code(400);
            exit();

        }

        mysqli_stmt_bind_result($stmt, $id, $username, $isEmail, $email, $isPhone, $phone, $phonePrefix);


        if(mysqli_stmt_fetch($stmt))
        {
            if(( $isEmail==TRUE && isset($email)) && ($isPhone==TRUE && isset($phone)))
            {
                echo json_encode(array('email' => $email, 'phone' =>$phone, 'phonePrefix' =>$phonePrefix));
                http_response_code(200);
                exit();
                //have email and phone
            }
            else if(( $isEmail==TRUE && isset($email)) && ($isPhone==FALSE || !isset($phone)) )
            {
                echo json_encode(array('email' => $email, 'phone' =>'NULL', 'phonePrefix' =>'NULL'));
                http_response_code(200);
                exit();
                // have email
            }
            
            else if(( $isEmail==FALSE || !isset($email)) && ($isPhone==TRUE && isset($phone)))
            {
                echo json_encode(array('email' => 'NULL', 'phone' =>$phone, 'phonePrefix' =>$phonePrefix));
                http_response_code(200);
                exit();
                //have phone
            }
            else
            {
                echo json_encode(array('error'=>'no info','email' => 'NULL', 'phone' =>'NULL', 'phonePrefix' =>'NULL'));
                http_response_code(400);
                exit();
                // no email or phone
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