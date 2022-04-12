<?php


header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


$rawData = file_get_contents("php://input");
$data = json_decode($rawData);


include_once '../../Config/dbc.php';




// // // $db_found = new mysqli(DB_SERVER, DB_USER, DB_PASS, $database );

// // // $SQL = $db_found->prepare("UPDATE members SET username=?, password=? WHERE email=?");

// // // $SQL->bind_param('sss', $uName, $passW, $email);
// // // $SQL->execute();



if(isset($data->key))
{
    $key = $data->key;
     
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'key'));
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
    
#, public_key_last_updated=?
$sql = "UPDATE users SET public_key=? WHERE id=?;";

if($stmt = mysqli_prepare($connection, $sql))
{
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ss", $key,$id1);
    if(mysqli_stmt_execute($stmt))
    {

        
        echo json_encode(array('success' => 'setup'));
        http_response_code(200);
        exit();        
        
    }
    else
    {
        
        //, 'token'=>'1234=8oy4252njk'
        echo json_encode(array('error' => 'fail'));
        http_response_code(400);
        exit();
    
    }


}
else
{
    echo "fuck";
}

// echo json_encode(array('errconnectionor' => 'username taken'));
http_response_code(500);
exit();   


