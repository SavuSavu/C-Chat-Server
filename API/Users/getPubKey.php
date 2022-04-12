<?php


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData);


include_once '../../Config/dbc.php';

if(isset($data->jwt))
{
    $jwt = $data->jwt;

        include '../../verifyJWT.php';
        $id = TestJWT($jwt);
        if( $id !=0)
        {#
            $id1 = $id;
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
    


// if(isset($data->id1))
// {
//     $id1 = $data->id1;
     
// }
// else
// {
//     echo json_encode(array('error' => 'empty variables', 'variable missing' => 'id1'));
//     http_response_code(400);
//     exit();   
// }



if(isset($data->id2))
{
    $id2 = $data->id2;
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'id2'));
    http_response_code(400);
    exit();   
}

$sql = 'SELECT id FROM friends WHERE id_user_1=? AND id_user_2=? OR id_user_1 =? and id_user_2=?;';

if($stmt = mysqli_prepare($connection, $sql))
{
    
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssss", $id1, $id2, $id2, $id1);
    

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt))
    {
    

        // store results
        $results = mysqli_stmt_store_result($stmt);

        if(mysqli_stmt_num_rows($stmt) < 1)
        {
    
            echo json_encode(array('error' => 'Not friends'));
            http_response_code(400);
            exit();

        }
        // else continue    
    }
    else
    {
        http_response_code(500);
        exit();        
    }
}
else
{
    http_response_code(500);
    exit();      
}



$sql = "SELECT public_key FROM users WHERE id = ?;";
if($stmt = mysqli_prepare($connection, $sql))
{

    

    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $id2);
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt))
    {

        // // store results
        // $results = mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) < 1)
        {
            
            //, 'token'=>'1234=8oy4252njk'
          //  echo json_encode(array('error' => 'no user with id'.$id2));
            // http_response_code(400);
            // exit();

        }

        mysqli_stmt_bind_result($stmt, $username);

        if(mysqli_stmt_fetch($stmt))
        {


            // echo $username;
            // echo strlen($username);
            // echo $pubkey;
            http_response_code(200);
            echo json_encode(array('pubKey'=>$username));
            exit();
        }





    }
    else
    {
        // echo json_encode(array('errconnectionor' => 'username taken'));
        // // http_response_code(500);
        // // exit();        

    }




}

