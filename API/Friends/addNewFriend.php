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

        if(mysqli_stmt_num_rows($stmt) == 1)
        {
    

            echo json_encode(array('error' => 'already friends'));
            http_response_code(400);
            exit();



            //$data = array('email' => $row["RecipeState"] )
            // data

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





$sql = "INSERT INTO friends (id_user_1, id_user_2,banned_by_1,banned_by_2, admin_1, admin_2, is_group, id_group, name) VALUES (?,?,?,?,?,?,?,?,?);";

if($stmt = mysqli_prepare($connection, $sql))
{
    $T = TRUE;
    $F = FALSE;
    $b1 = 0;
    $b2 = 1;
    mysqli_stmt_bind_param($stmt, "sssssssss",$id1,$id2,$b1,$b1,$b2,$b2,$b1,$b1,$b1);
    if(mysqli_stmt_execute($stmt))
    {

        echo json_encode(array('success' => 'friendship created'));
        http_response_code(200);
        exit();

    }
    else
    {
        printf("Error: %s.\n", mysqli_stmt_error($stmt));
        echo json_encode(array('error' => 'friendship not created'));
        http_response_code(400);
        exit();
    
    }


}    




// echo json_encode(array('errconnectionor' => 'username taken'));
http_response_code(500);
exit();   

echo "fuck";






