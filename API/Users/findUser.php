<?php
//need auth 

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

if(isset($data->username))
{
    $username = $data->username;

     
}

// else
// {
//     echo json_encode(array('error' => 'empty variables', 'variable missing' => 'username'));
//     http_response_code(400);
//     exit();   
// }


if(isset($data->randomID))
{
    $randomID = $data->randomID;
     
}


if(!isset($randomID) && !isset($username))
{
    echo json_encode(array('error' => 'empty variables'));
    http_response_code(400);    
    exit();
}
elseif(!isset($randomID) && isset($username))
{
    // search by username 
    $sql = "SELECT id, id_random, username, public_key, avatar, avatar_icon, description FROM users WHERE username = ?;";

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
                echo json_encode(array('error' => 'no user with '.$username));
                http_response_code(400);
                exit();

            }

            mysqli_stmt_bind_result($stmt, $id, $idRandom, $username, $publicKey, $avatar, $avatarIcon, $description );
            if(mysqli_stmt_fetch($stmt))
            {
                // echo $avatar;
                echo json_encode(array(
                    'username' => $username,
                    'idRandom' => $idRandom,
                    'id' => $id,
                    'pKey' => $publicKey,
                    'avatar'=> $avatar,
                    'avatarIcon' => $avatarIcon, 
                    'description' => $description ));
                http_response_code(200);
                
                exit();
            }

        }

    }


}
elseif(isset($randomID)&& !isset($username))
{
    // search by random id  
    $sql = "SELECT id, id_random, username, public_key, avatar, avatar_icon, description FROM users WHERE id_random = ?;";

    if($stmt = mysqli_prepare($connection, $sql))
    {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $randomID);

        if(mysqli_stmt_execute($stmt))
        {
            // store results
            $results = mysqli_stmt_store_result($stmt);
            // Check if username exists, if yes then verify password
            if(mysqli_stmt_num_rows($stmt) < 1)
            {
                
                echo json_encode(array('error' => 'no user with '.$randomID));
                http_response_code(400);
                exit();

            }

            mysqli_stmt_bind_result($stmt, $id, $idRandom, $username, $publicKey, $avatar, $avatarIcon, $description );

            if(mysqli_stmt_fetch($stmt))
            {

                echo json_encode(array(
                    'username' => $username,
                    'idRandom' => $idRandom,
                    'id' => $id,
                    'pKey' => $publicKey,
                    'avatar'=> $avatar,
                    'avatarIcon' => $avatarIcon,
                    'description' => $description));
                http_response_code(200);
                exit();
            }

            

        }

    }



}
else
{
    echo json_encode(array('error' => 'empty variables'));
    http_response_code(400);    
    exit();

}

http_response_code(500);    
exit();