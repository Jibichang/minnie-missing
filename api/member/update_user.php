<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

include_once '../config/database.php';
include_once '../objects/memberObject.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
$member = new Member($db);

$data = json_decode(file_get_contents("php://input"));
$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){
  // if decode succeed, show user details
  try {
    // decode jwt
    $decoded = JWT::decode($jwt, $key, array('HS256'));

    $member->guest_name = $data->name;
    $member->guest_email = $data->email;
    $member->guest_pass = $data->password;
    $member->guest_id = $decoded->data->id;

    if($member->update()){
      $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
          "id" => $member->guest_id,
          "name" => $member->guest_name,
          "email" => $member->guest_email,
          "password" => $member->guest_pass
        )
      );
      $jwt = JWT::encode($token, $key);

      http_response_code(200);
      // response in json format
      echo json_encode(
        array(
          "message" => "User was updated.",
          "jwt" => $jwt
        )
      );
    }
    // message if unable to update user
    else{
      http_response_code(401);
      echo json_encode(array("message" => "Unable to update user."));
    }
  }

  catch (Exception $e){
    http_response_code(401);

    echo json_encode(array(
      "message" => "Access denied.",
      "error" => $e->getMessage()
    ));
  }
}
else{
    http_response_code(401);
    echo json_encode(array("message" => "Access denied."));
}
?>
