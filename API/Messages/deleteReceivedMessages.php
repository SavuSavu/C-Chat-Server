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
            $userID =$id;
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
    

if(isset($data->listOfMessages))
{
    $listOfMessages = $data->listOfMessages;
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'listOfMessages'));
    http_response_code(400);
    exit();   
}


$sql = "SELECT id FROM messages WHERE id_from =? ";


$listOfMessagesAllowedToBeDeleted = array();
if($stmt = mysqli_prepare($connection, $sql))
{
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $userID);

    if(mysqli_stmt_execute($stmt))
    {
        // store results
        //$results = mysqli_stmt_store_result($stmt);
        $results = mysqli_stmt_get_result($stmt);
        


        $i = 0;
        $messages = array();
        
        while ($row = mysqli_fetch_assoc($results))
        {
            $i++;
            // echo "hello";
            // echo $row["id"];
            array_push($listOfMessagesAllowedToBeDeleted, $row["id"]);
            
         
        }



        

    }
    else{
        echo json_encode(array('error' => 'empty variables'));
        http_response_code(400);
        exit();   
    }

}

// for($x = 0 ;$x<=count($listOfMessagesAllowedToBeDeleted); $x++)
// {
// echo $listOfMessagesAllowedToBeDeleted[$x];

// }
$ListOfDeletedMessages = array();
$ListNotDeletedMessages = array();

$x = count($listOfMessages);
$i = 0;
while($i<$x)
{

    $sql = "DELETE FROM messages WHERE id=? AND id_to=?;";
    if($stmt = mysqli_prepare($connection, $sql))
    {   
    

        mysqli_stmt_bind_param($stmt, "ss", $listOfMessages[$i],$userID );
        if(mysqli_stmt_execute($stmt))
        {
            //$results = mysqli_stmt_get_result($stmt);

            array_push($ListOfDeletedMessages, $listOfMessages[$i]);
        }
        else
        {
            array_push($ListNotDeletedMessages, $listOfMessages[$i]);

        }
        $i+=1;
        
        
        
    
    }
    else
    {
        array_push($ListNotDeletedMessages, $listOfMessages[$i-1]);
    }
} 

if (count($listOfMessages) == count($ListOfDeletedMessages))
{
    // echo json_encode(array('success' => $ListNotDeletedMessages));

    echo json_encode(array('success' => $ListOfDeletedMessages));
    http_response_code(200);
    exit();
}
else
{

    echo json_encode(array('error' => 'some messages were not deleted from the db',"messagesDeleted"=>$ListOfDeletedMessages, "Not deleted"=>$ListNotDeletedMessages));
    http_response_code(400);
    exit();

}
http_response_code(400);exit();   