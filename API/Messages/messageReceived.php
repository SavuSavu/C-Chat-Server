<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

$rawData = file_get_contents("php://input");
$data = json_decode($rawData);


include_once '../../Config/dbc.php';

$idList = NULL;

if(isset($data->idList))
{
    $idList = $data->idList;
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'idList'));
    http_response_code(400);
    exit();   
}

// echo 1;
$numOfMessages = count($idList);
// echo $idList;
echo $numOfMessages;
if ($numOfMessages < 1)
{
    echo 1;
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'idList'));
    http_response_code(400);
    exit();   

} 
else if ($numOfMessages == 1)
{
    //"UPDATE MyGuests SET lastname='Doe' WHERE id=2";
    $sql = "UPDATE messages set status=? WHERE id=?;";
    if($stmt = mysqli_prepare($connection, $sql))
    {

        echo 2;
        mysqli_stmt_bind_param($stmt, "ss", 2,$idList );
        if(mysqli_stmt_execute($stmt))
        {
            echo json_encode(array('success' => 'message status changed to received'));
            http_response_code(200);
            exit();

        }
        else
        {        
            echo 4;
            echo json_encode(array('error' => 'cant send message to this user'));
            http_response_code(400);
            exit();

        }
    }

    http_response_code(400);exit();     
}
// else if (($numOfMessages > 1))
// {
//     $x = 0;
//     foreach ($idList as $id )
//     {
//         $sql = "UPDATE messages set status=? WHERE id=?;";
//         if($stmt = mysqli_prepare($connection, $sql))
//         {

//             mysqli_stmt_bind_param($stmt, "ss", 2,$idList );
//             if(mysqli_stmt_execute($stmt))
//             {
//                 // store results
//                 $results = mysqli_stmt_store_result($stmt);

//                 if(mysqli_stmt_num_rows($stmt) != 1)
//                 {
            

//                     echo json_encode(array('error' => 'cant send message to this user'));
//                     http_response_code(400);
//                     exit();



//                     #$data = array('email' => $row["RecipeState"] )


//                 }
//                 else
//                 {
//                     $x = $x +1;
           

//                 }
           
                


//             }
//             else{http_response_code(500);exit();}

//         }
//         else{http_response_code(500);exit();}

//     }

//     if($x == $numOfMessages)
//     {
//         echo json_encode(array('success' => 'all message status changed to received'));
//         http_response_code(200);
//         exit();


//     }
//     else
//     {
//         echo json_encode(array('success' => strval($x)+' of messages status changed to received'));
//         http_response_code(200);
//         exit();

//     }

// }
// else 
// {

//     http_response_code(500);
//     exit();  
// }

// exit();
