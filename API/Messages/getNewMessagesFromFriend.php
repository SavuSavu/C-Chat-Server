<?php


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData);


include_once '../../Config/dbc.php';


// if(isset($data->toID))
// {
//     try
//     {
//         $toID = intval($data->toID);
//         // echo $toID;
//         // $toID = intval($toID);
//     }
//     catch (Exception $e)
//     {
//         // echo 'Caught exception: ',  $e->getMessage(), "\n";
        
//         echo json_encode(array('error' => 'id is not an integer', 'exception' => $e->getMessage()));
//         http_response_code(400);
//         exit();   
        
//     }
    
    
// }
// else
// {
//     echo json_encode(array('error' => 'empty variables', 'variable missing' => 'toID'));
//     http_response_code(400);
//     exit();   
// }


if(isset($data->jwt))
{
    $jwt = $data->jwt;

        include '../../verifyJWT.php';
        $id = TestJWT($jwt);
        if( $id !=0)
        {#
            $toID =$id;
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

    
if(isset($data->fromID))
{
    try
    {
        $fromID = intval($data->fromID);
        // echo $toID;
        // $toID = intval($toID);
    }
    catch (Exception $e)
    {
        // echo 'Caught exception: ',  $e->getMessage(), "\n";
        
        echo json_encode(array('error' => 'id is not an integer', 'exception' => $e->getMessage()));
        http_response_code(400);
        exit();   
        
    }

     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'toID'));
    http_response_code(400);
    exit();   
}


$sql = "SELECT * FROM messages WHERE id_to=? AND id_from=?;";



if($stmt = mysqli_prepare($connection, $sql))
{
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ss", $toID, $fromID);

    if(mysqli_stmt_execute($stmt))
    {
        // store results
        //$results = mysqli_stmt_store_result($stmt);
        $results = mysqli_stmt_get_result($stmt);
        
        //$results =  mysqli_query($connection, $sql);
        

        
        // echo (count($row)); 
        // if (count($row) 

        $i = 0;
        $messages = array();
        while ($row = mysqli_fetch_assoc($results)) {
            $i++;
    
            $message = array();
            $message['id'] = $row["id"];
            $message['message'] = $row["message"];
            $message['key'] = $row["message_key"];
            $message['fromID'] = $row["id_from"];
            array_push($messages, $message);
            // $messages[] += $message;
    
            // printf("%s (%s)\n", $row["receive_at_server"], $row  ["message_key"] );
        }
        // echo"hello";
        // echo $message;

        $j = $i;
        if ($i < 1)
        {


            echo json_encode(array('success' => 'no new messages'));
            http_response_code(200);
            exit();  
        }
        else
        {
                





            #$response = array();
            #array_push($response, $messages);
            $noOfMess = strval($i). "new messages";
            echo json_encode(array('success' => "New Messages", 'new Messages'=> strval($i), "m"=> $messages));
            http_response_code(200);
            exit();
        }

        // // Check if username exists, if yes then verify password
        // if(mysqli_stmt_num_rows($stmt) < 1)
        // {
            
        //     //, 'token'=>'1234=8oy4252njk'
        //     echo json_encode(array('response' => 'no new messages'));
        //     http_response_code(200);
        //     exit();

        // }
        // else
        // {


        // }

        // mysqli_stmt_bind_result($stmt, $id, $idRandom, $username);
    

        

    }

}



http_response_code(500);
exit();   





























