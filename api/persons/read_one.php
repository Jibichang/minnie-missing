<?php
  header("Content-Type: application/json; charset=UTF-8");

  include_once '../config/database.php';
  include_once '../objects/missingPersonObject.php';

  $database = new Database();
  $connection = $database->getConnection();

  $missing = new MissingPersons($connection);
  //$data = json_decode(file_get_contents("php://input"));

  $stmt = $missing->read();
  $count = $stmt->rowCount();

  if($count > 0){
      $missing = array();
      $missing["body"] = array();
      $missing["count"] = $count;

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $missing_arr = array(
              "pname"=> $pname,
              "fname"=> $fname,
              "lname"=> $lname,
              "gender"=> $gender,
              "age"=> $age,
              "place"=> $place,
              "subdistrict"=> $subdistrict,
              "district"=> $district,
              "city"=> $city,
              "detail"=> $detail,
              "specific"=> $specific,
              "status"=> $status,
              "type_id"=> $type_id,
              "guest_id"=> $guest_id

          );
          array_push($missing["body"], $missing_arr);
      }
      echo json_encode($missing,JSON_UNESCAPED_UNICODE);
  }  else {
      echo json_encode( array( "body" => array(), "count" => 0) );
  }
?>
