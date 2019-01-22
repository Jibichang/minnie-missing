<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../objects/missingPersonObject.php';

$database = new Database();
$db = $database->getConnection();

$missing = new MissingPersons($db);

$data = json_decode(file_get_contents("php://input"));

if(
    !empty($data->id) &&
    !empty($data->pname) &&
    !empty($data->fname) &&
    !empty($data->lname) &&
    !empty($data->gender) &&
    !empty($data->age) &&
    !empty($data->place) &&
    !empty($data->subdistrict) &&
    !empty($data->district) &&
    !empty($data->city) &&
    !empty($data->detail) &&
    !empty($data->specific) &&
    !empty($data->status) &&
    !empty($data->type_id) &&
    !empty($data->guest_id)
){

    // set product property values
    //$missing->id = $data->id;
    $missing->pname = $data->pname;
    $missing->fname = $data->fname;
    $missing->lname = $data->lname;
    $missing->gender = $data->gender;
    $missing->age = $data->age;
    $missing->place = $data->place;
    $missing->subdistrict = $data->subdistrict;
    $missing->district = $data->district;
    $missing->city = $data->city;
    $missing->detail = $data->detail;
    $missing->specific = $data->specific;
    $missing->status = $data->status;
    $missing->type_id = $data->type_id;
    $missing->guest_id = $data->guest_id;
    $missing->reg_date = $data->reg_date;

    if($missing->create()){

      // set response code
      http_response_code(200);

      // display message: user was created
      echo json_encode(array("message" => "User was created."));
    }

    // message if unable to create user
    else{

      // set response code
      http_response_code(400);

      // display message: unable to create user
      echo json_encode(array("message" => "Unable to create user."));
    }
}
?>
