<?php

require_once 'jwt/src/BeforeValidException.php';
require_once 'jwt/src/ExpiredException.php';
require_once 'jwt/src/SignatureInvalidException.php';
require_once 'jwt/src/JWT.php';


use \Firebase\JWT\JWT;
$secret_key = "SUperSecretKey";



function TestJWT($jwt)
{

    if($jwt)
    {
    
        try 
        {
            $secret_key = "SUperSecretKey";
    
            $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
    
            return $decoded->id;
    
        }
        catch (Exception $e)
        {
            return 0;
        }
    
    }

}

















#$jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDB9.KcNaeDRMPwkRHDHsuIh1L2B01VApiu3aTOkWplFjoYI";

// // $jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MjMsInVzZXJuYW1lIjoic2F2dSJ9.kZ5_SnCOBPWF1q_NCEt1R-lmced82OuTolR_QKQuxcE";

// // if($jwt)
// // {

// //     try 
// //     {


// //         $decoded = JWT::decode($jwt, $secret_key, array('HS256'));

// //         // Access is granted. Add code of the operation here 

// //         echo json_encode(array(
// //             "message" => (array) $decoded->id
// //             #"error" => $e->getMessage()
// //         ));

// //     }
// //     catch (Exception $e)
// //     {
// //         http_response_code(401);

// //         echo json_encode(array(
// //             "message" => "Access denied.",
// //             "error" => $e->getMessage()
// //         ));
// //     }

// // }