<?php
  header("Content-Type: application/json; charset=UTF-8");

  include_once '../config/database.php';
  include_once '../objects/typeObject.php';

  $database = new Database();
  $connection = $database->getConnection();

  $type = new Type($connection);

  $stmt = $type->read();
  $count = $stmt->rowCount();

  if($count > 0){
      $type = array();
      $type["body"] = array();
      $type["count"] = $count;

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          extract($row);

          $type_arr = array(
            "type_id" => $type_id,
            "type_name" => $type_name
          );
          array_push($type["body"], $type_arr);
      }
      echo json_encode($type);
  }  else {
      echo json_decode( array( "body" => array(), "count" => 0) );
  }
?>
