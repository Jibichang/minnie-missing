<?php

// header("Access-Control-Allow-Origin: http://localhost/rest-api-authentication-example/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../config/core.php';
include_once '../objects/missingPersonObject.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

// initialize object
$missing = new MissingPersons($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query products
$stmt = $missing->search($keywords);
$num = $stmt-> rowCount();

// check if more than 0 record found
if($num>0){

    // products array
    $missing_arr=array();
    $missing_arr["records"]=array();

    // retrieve our table contents
    // fetch() is faster than fetchAll()
    // http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $missing_item = array(
            "fname"=> $fname,
            "lname"=> $lname,
            "detail"=> $detail,
            

        );

        array_push($missing_arr["records"], $missing_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show products data
    echo json_encode($missing_arr,JSON_UNESCAPED_UNICODE);
}

else{
    // set response code - 404 Not found
    http_response_code(404);

    // tell the user no products found
    echo json_encode(
        array("message" => "No person found.")
    );
}
?>
