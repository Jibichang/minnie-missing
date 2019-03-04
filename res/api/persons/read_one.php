<?php
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Origin: *");
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: GET");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


  include_once '../config/database.php';
  include_once '../objects/missingPersonObject.php';

  $database = new Database();
  $connection = $database->getConnection();

  $missing = new MissingPersons($connection);
  //$data = json_decode(file_get_contents("php://input"))


  //$count = $stmt->rowCount();

  $keywords=isset($_GET["id"]) ? $_GET["id"] : "0";
  // query products
//  echo $keywords;
  // $stmt = $missing->search($keywords);

  if($keywords=="0"){
      $stmt = $missing->read();

      $missing = array();
      $missing["body"] = array();
    //  $missing["count"] = $count;
    if($stmt->rowCount() > 0){
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
          // print_r ($row);
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
            "height"=> $height,
            "weight"=> $weight,
            "shape"=> $shape,
            "hairtype"=> $hairtype,
            "haircolor"=> $haircolor,
            "skintone"=> $skintone,
            "upperrwaist"=> $upperwaist,
            "uppercolor"=> $uppercolor,
            "lowerwaist"=> $lowerwaist,
            "lowercolor"=> $lowercolor,
            "detail_etc"=> $detail_etc,
            "special"=> $special,
            "type_id"=> $type_id,
            "guest_id"=> $guest_id,
            "status"=> $status,
            "reg_date"=> $reg_date

          );
          array_push($missing["body"], $missing_arr);
      }
    }
      echo json_encode($missing, JSON_UNESCAPED_UNICODE);
  }  else if($keywords != "0"){

    $stmt = $missing->read_one($keywords);
    $missing = array();
    $missing["body"] = array();


  if($stmt->rowCount() > 0){


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // print_r ($row);
        extract($row);

        $missing_arr = array(
            "plost_id"=> $plost_id,
            "pname"=> $pname,
            "fname"=> $fname,
            "lname"=> $lname,
            "gender"=> $gender,
            "age"=> $age,
            "place"=> $place,
            "subdistrict"=> $subdistrict,
            "district"=> $district,
            "city"=> $city,
            "height"=> $height,
            "weight"=> $weight,
            "shape"=> $shape,
            "hairtype"=> $hairtype,
            "haircolor"=> $haircolor,
            "skintone"=> $skintone,
            "upperrwaist"=> $upperwaist,
            "uppercolor"=> $uppercolor,
            "lowerwaist"=> $lowerwaist,
            "lowercolor"=> $lowercolor,
            "detail_etc"=> $detail_etc,
            "special"=> $special,
            "type_id"=> $type_id,
            "guest_id"=> $guest_id,
            "status"=> $status,
            "reg_date"=> $reg_date


        );
        array_push($missing["body"], $missing_arr);
    }

      echo json_encode($missing, JSON_UNESCAPED_UNICODE);
    }

}
?>
