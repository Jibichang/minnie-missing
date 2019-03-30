<?php
// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../config/core.php';
include_once '../objects/missingPersonObject.php';
include_once '../objects/missingSearchIRObject.php';
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
// initialize object
$missing = new MissingPersons($db);
$data = json_decode(file_get_contents("php://input"));
// get keywords
//$keywords=isset($_POST["s"]) ? $_POST["s"] : "";
$missing->fname = $data->fname;
$missing->lname = $data->lname;
$missing->gender = $data->gender;
$missing->age = $data->age;

$missing->city = $data->city;
$missing->district = $data->district;
$missing->subdistrict = $data->subdistrict;
$missing->place = $data->place;

$missing->height = $data->height;
$missing->shape = $data->shape;

$missing->hairtype = $data->hairtype;
$missing->haircolor = $data->haircolor;

$missing->upperwaist = $data->upperwaist;
$missing->uppercolor = $data->uppercolor;
$missing->lowerwaist = $data->lowerwaist;
$missing->lowercolor = $data->lowercolor;

$missing->skintone = $data->skintone;
$missing->type_id = $data->type_id;
$missing->status = $data->status;
$missing->detail_etc = $data->detail_etc;
$missing->special = $data->special;
//$missing->type_id = $data->type_id;
//$missing->missing_person = $data->fname.''.$data->lname;

// $str =  $row["detail_etc"]." ".$row["special"]." ".$row["age"]." ".
//         $row["city"]." ".$row["district"]." ".$row["subdistrict"]." ".$row["place"]." ".
//         $row["height"]." ".$row["skintone"]." ".$row["shape"]." ".
//         $row["hairtype"]." H".$row["haircolor"]." ".
//         $row["upperwaist"]." U".$row["uppercolor"]." ".
//         $row["lowerwaist"]." L".$row["lowercolor"];

$stmt = $missing->search();
$q =  $data->fname." ".$data->fname." ".
      $data->lname." ".$data->lname." ".
      $missing->city." ".$missing->city." ".
      $missing->district." ".
      $missing->subdistrict." ".
      $missing->place." ".
      $missing->age." ".

      $missing->upperwaist." ".
      $missing->uppercolor." ".
      $missing->lowerwaist." ".
      $missing->lowercolor." ".

      $missing->detail_etc." ".$missing->detail_etc." ".
      $missing->special." ".$missing->special." ".$missing->special." ".
      $missing->skintone." ".
      $missing->hairtype." ".
      $missing->haircolor." ".
      $missing->shape." ";

      $query = $q;
      if ($data->mode == 0) {
        $query = $data->fname." ".$data->lname." ".$data->fname." ".$data->lname." ".$q;
      }else if ($data->mode == 1) {
        $query = $missing->city." ".$q;
      }else if ($data->mode == 2) {
        $query = $q." ".
                $missing->detail_etc." ".
                $missing->detail_etc." ".
                $missing->skintone." ".
                $missing->hairtype." ".
                $missing->haircolor." ".
                $missing->special;

      }else {
        $query = $q;
      }
$result = $missing->searchIR($query);
$num = $stmt->rowCount();

if ($num >  0) {
  // set response code - 200 OK
  http_response_code(200);
  // show products data
  echo json_encode($result);

  // $missing_arr=array();
  // $missing_arr["body"]=array();
  // while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
  // {
  //   extract($row);
  //   $missing_item = array(
  //     "pname"=> $pname,
  //     "fname"=> $fname,
  //     "lname"=> $lname,
  //     "gender"=> $gender,
  //     "age"=> $age,
  //     "place"=> $place,
  //     "subdistrict"=> $subdistrict,
  //     "district"=> $district,
  //     "city"=> $city,
  //     "height"=> $height,
  //     "weight"=> $weight,
  //     "shape"=> $shape,
  //     "hairtype"=> $hairtype,
  //     "haircolor"=> $haircolor,
  //     "skintone"=> $skintone,
  //     "upperrwaist"=> $upperwaist,
  //     "uppercolor"=> $uppercolor,
  //     "lowerwaist"=> $lowerwaist,
  //     "lowercolor"=> $lowercolor,
  //     "detail_etc"=> $detail_etc,
  //     "special"=> $special,
  //     "type_id"=> $type_id,
  //     "guest_id"=> $guest_id,
  //     "status"=> $status,
  //     "reg_date"=> $reg_date
  //   );
  //   array_push($missing_arr["body"], $missing_item);
  //   // array_push($sim_result, $row["detail_etc"]); // detail (doc)
  // }
  // echo json_encode($missing_arr);

}
else {
  // set response code - 404 Not found
  http_response_code(404);
  // tell the user no products found
  echo json_encode(
      array("message" => "No person found.")
  );
}

?>
