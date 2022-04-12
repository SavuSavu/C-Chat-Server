<?php

// {
//     "username" : "savu6",
//     "password" : "parola",
//     "passwordRepeat": "parola",
//     "isEmail": false,
//     "isPhone": false,
//     "phone" : "0727567175",
//     "phonePrefix": "+04",
//     "email": ""

// }



// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');


$rawData = file_get_contents("php://input");
$data = json_decode($rawData);
// require '../../Config/dbc.php';

include_once '../../Config/dbc.php';

// print_r($data);



// echo file_get_contents('php://input');
// $obj = json_decode($jsonobj);

// echo $obj->Peter;

// echo $data["username"];
if(isset($data->username))
{
    $username = $data->username;
     
}
if(isset($data->password))
{
    $password = $data->password;
    
}

if(isset($data->passwordRepeat))
{
    $passwordRepeat = $data->passwordRepeat;
  
}
if(isset($data->isEmail))
{
    $isEmail = $data->isEmail;
        
}
// echo "hello";
// echo $isEmail;
// echo $data->isEmail;
if(isset($data->isPhone))
{
    $isPhone = $data->isPhone;
    
}



// echo $username;
// if(empty($isPhone))
// {
//     echo 'username';
// }
// try
// {
//     $username = $data->username;
// }
// catch (Exception $e)
// {
//     echo 'Caught exception: ',  $e->getMessage(), "\n";
// }

$email = "NULL";
$phone = NULL;
$phonePrefix = NULL;

// echo "{'username':'".$username ."'}";

//Create object before assigning   
// $myObj =  new stdClass();
// $myObj->username = $username;
// $myObj->password = $password;
// $myObj->passwordRepeat= $passwordRepeat;

// $myJSON = json_encode($myObj);

// echo $myJSON;    


if ($isEmail==TRUE && isset($data->email) && $data->email !="null" )
{
    $email = $data->email;
}
else if ($isEmail==FALSE)
{
    $email = NULL;


}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'isEmail'));
    http_response_code(400);

    exit();   
}

if ($isPhone==TRUE && isset($data->phone) && isset($data->phonePrefix) )
{
    $phonePrefix = $data->phonePrefix;
    $phone = $data->phone;

}    
else if($isPhone == FALSE )
{
    $phonePrefix = NULL;
    $phone = NULL;
 
}
else
{
    echo json_encode(array('error' => 'empty variables', 'variable missing' => 'isPhone'));
    http_response_code(400);

    exit();
}

// if one of the inputs is empty 
if (empty($username) || empty($password) || empty($passwordRepeat))
{


   echo json_encode(array('error' => 'empty variables2'));
   http_response_code(400);

   exit();

}


// if isEmail is True but the email is missing return error  
if ($isEmail == TRUE && empty($email))
{
    echo json_encode(array('error' => 'empty variable', 'variable missing' => 'email'));
    http_response_code(400);

    exit();

}

// if isPhone is True but the phone number is missing return error
if ($isPhone == TRUE && empty($phone))
{

    echo json_encode(array('error' => 'empty variable', 'variable missing' => 'phone'));
    http_response_code(400);

    exit();

}

// if isPhone is True but prefix is missing return error
if ($isPhone == TRUE && empty($phonePrefix))
{
    echo json_encode(array('error' => 'empty variable', 'variable missing' => 'phonePrefix'));
    http_response_code(400);

    exit();

}

if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) 
{
    echo json_encode(array('error' => 'invalid character'));
    http_response_code(400);

    exit();

}


if ( $password !== $passwordRepeat)
{
    echo json_encode(array('error' => 'passwords are not matching'));
    http_response_code(400);
    exit();

}

CheckDBForSimilar($username, "username", $connection);
// CheckDBForSimilar($email, "email", $connection);
// CheckDBForSimilar($phone, "phone", $connection);

if($isEmail)
{
    CheckDBForSimilar($email, "email", $connection);
}
if($isPhone)
{
    CheckDBForSimilar($phone, "phone", $connection);
}



function CheckDBForSimilar($information, $searchFor, $connection)
{
    // prepare SELECT statement for $searchFor 
    $sql = 'SELECT id FROM users WHERE '.$searchFor.' = ?;';

    if($stmt = mysqli_prepare($connection, $sql))
    {
        
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $information);
        

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt))
        {
        

            // store results
            $results = mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt) == 1)
            {
        

                echo json_encode(array('error' => $searchFor.' taken'));
                http_response_code(400);
                exit();

            }
            
        }
        else
        {
            // echo json_encode(array('errconnectionor' => 'username taken'));
            http_response_code(500);
            exit();        

        }

    }

}

$sql = "INSERT INTO users (username, password, is_email, email, is_phone, phone_prefix, phone) VALUES (?, ?, ?, ?, ?, ?, ?);";

if($stmt = mysqli_prepare($connection, $sql))
{

    $password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
    

    // Bind variables to the prepared statement as parameters
    $x = mysqli_stmt_bind_param($stmt, "sssssss", $username, $password, $isEmail, $email, $isPhone, $phonePrefix, $phone);
    

    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt))
    {
        echo json_encode(array('account' => 'account created'));
        http_response_code(200);
        exit();
    } 
    else
    {
        echo json_encode(array('error' => 'account not created'));
        http_response_code(400);
        exit();

    }
    
}

// echo json_encode(array('errconnectionor' => 'username taken'));
http_response_code(501);
exit();   

echo "fuck";

// ALTER TABLE users
// ADD is_email BOOLEAN NOT NULL AFTER username;


// // prepare SELECT statement for username 
// $sql = 'SELECT id FROM users WHERE username = ?;';

// if($stmt = mysqli_prepare($connection, $sql))
// {
//     // Bind variables to the prepared statement as parameters
//     mysqli_stmt_bind_param($stmt, "s", $username);

//     // Attempt to execute the prepared statement
//     if(mysqli_stmt_execute($stmt))
//     {
//         // store results
//         mysqli_stmt_store_result($stmt);

//         if(mysqli_stmt_num_rows($stmt) == 1)
//         {
//             echo json_encode(array('error' => 'username taken'));
//             http_response_code(400);
//             exit();

//         }
        
//     }
//     else
//     {
//         // echo json_encode(array('error' => 'username taken'));
//         http_response_code(500);
//         exit();        

//     }

// }

// // Prepare a select statement for email
// $sql = "SELECT id FROM users WHERE email = ?";

// if($stmt = mysqli_prepare($connection, $sql))
// {
//     // Bind variables to the prepared statement as parameters
//     mysqli_stmt_bind_param($stmt, "s", $email);

//     // Attempt to execute the prepared statement
//     if(mysqli_stmt_execute($stmt))
//     {
//         // store results
//         mysqli_stmt_store_result($stmt);
//         if(mysqli_stmt_num_rows($stmt) == 1)
//         {
//             echo json_encode(array('error' => 'email taken'));
//             http_response_code(400);
//             exit();

//         }
//         else
//         {
//             //pass
//         }
//     }
//     else
//     {
//         http_response_code(500);
//         exit();        
//     }

// }






















// // {
// //     "username" : "savu",
// //     "password" : "parola",
// //     "passwordRepeat": "parola",
// //     "isEmail": false,
// //     "isPhone": false
// // }







// // ///    is this going to work???? 

// // // check if the request came from the right place 
// // if (!isset($_POST['username']) ||!isset($_POST['password']) || !isset($_POST['passwordRepeat']) || !isset($_POST['isEmail']) || !isset($_POST['isPhone'])   )  
// // {
// //     echo json_encode(array('error' => 'empty variables1'));
// //     http_response_code(400);
 
// //     exit();
 
// // }

// // // get the DB connection 
// // include_once '../../Config/dbc.php';


// // $username = mysqli_real_escape_string($connection, $_POST['username']);
// // $password = mysqli_real_escape_string($connection, $_POST['password']);
// // $passwordRepeat = mysqli_real_escape_string($connection, $_POST['passwordRepeat']);

// // $isEmail = mysqli_real_escape_string($connection, $_POST['isEmail']);
// // $email = mysqli_real_escape_string($connection, $_POST['email']);

// // $isPhone = mysqli_real_escape_string($connection, $_POST['isPhone']);
// // $phonePrefix = mysqli_real_escape_string($connection, $_POST['phonePrefix']);
// // $phone = mysqli_real_escape_string($connection, $_POST['phone']);



