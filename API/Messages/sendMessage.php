<?php


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData);


include_once '../../Config/dbc.php';



if(isset($data->toID))
{
    $toID = $data->toID;
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'toID'));
    http_response_code(400);
    exit();   
}


if(isset($data->jwt))
{
    $jwt = $data->jwt;

        include '../../verifyJWT.php';
        $id = TestJWT($jwt);
        if( $id !=0)
        {#
            $fromID = $id;
        }
        else
        {
            echo json_encode(array('error' => 'Wrong JWT'));
            http_response_code(400);
            exit();   
        }
    
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'jwt'));
    http_response_code(400);
    exit();   
}


if(isset($data->message))
{
    $message = $data->message;
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'message'));
    http_response_code(400);
    exit();   
}



if(isset($data->messageKey))
{
    $messageKey = $data->messageKey;
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'messageKey'));
    http_response_code(400);
    exit();   
}

$sql = 'SELECT id FROM friends WHERE id_user_1=? AND id_user_2=? OR id_user_1 =? and id_user_2=?;';
if($stmt = mysqli_prepare($connection, $sql))
{
    
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssss", $toID, $fromID, $fromID, $toID);
    

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt))
    {
    

        // store results
        $results = mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) != 1)
        {
    

            echo json_encode(array('error' => 'cant send message to this user'));
            http_response_code(400);
            exit();



            #$data = array('email' => $row["RecipeState"] )


        }
        
    }
    else
    {
        // echo json_encode(array('errconnectionor' => 'username taken'));
        http_response_code(500);
        exit();        

    }

}
else
{

    http_response_code(500);
    exit();      
}






$status = 1;

$sql = 'INSERT INTO messages (id_from, id_to, message, message_key, status) VALUES (?,?,?,?,?);';


if($stmt = mysqli_prepare($connection, $sql))
{

    mysqli_stmt_bind_param($stmt, "sssss", $fromID, $toID, $message,$messageKey, $status);

    if(mysqli_stmt_execute($stmt))
    {

        echo json_encode(array('success' => 'message sent'));
        http_response_code(200);
        exit();

    }
    else
    {
        echo json_encode(array('error' => 'message not sent'));
        http_response_code(400);
        exit();
    
    }


}


http_response_code(500);exit();   
