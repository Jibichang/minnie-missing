<?php
class MissingPersons{
  private $connection;
  private $table_name = "plost";


  public $id;
  public $pname;
  public $fname;
  public $lname;
  public $gender;
  public $age;
  public $place;
  public $subdistrict;
  public $district;
  public $city;
  public $detail;
  public $specific;
  public $status;
  public $type_id;
  public $guest_id;
  public $reg_date;

  public function __construct($connection){
    $this->connection = $connection;

  }

  function read(){
     $query = "SELECT * FROM " . $this->table_name;
   $stmt = $this->connection->prepare($query);

     $stmt-> execute();
     return $stmt;
   }

   function delete(){

       // delete query
       $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

       // prepare query
       $stmt = $this->connection->prepare($query);

       // sanitize
       $this->id=htmlspecialchars(strip_tags($this->id));

       // bind id of record to delete
       $stmt->bindParam(1, $this->id);

       // execute query
       if($stmt->execute()){
           return true;
       }
       return false;
     }

     function create(){

    // query to insert record
      $query = "INSERT INTO
                " . $this->table_name . "
            SET
                id = :id,
                pname = :pname,
                fname = :fname,
                lname = :lname,
                gender = :gender,
                age = :age,
                place = :place,
                subdistrict = :subdistrict,
                district = :district,
                city = :city,
                detail = :detail,
                specific = :specific,
                status = :status,
                type_id = :type_id,
                guest_id = :guest_id,
                reg_date = NOW()";

    // prepare query
    $stmt = $this->connection->prepare($query);

    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));
    $this->pname=htmlspecialchars(strip_tags($this->pname));
    $this->fname=htmlspecialchars(strip_tags($this->fname));
    $this->lname=htmlspecialchars(strip_tags($this->lname));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->age=htmlspecialchars(strip_tags($this->age));
    $this->place=htmlspecialchars(strip_tags($this->place));
    $this->subdistrict=htmlspecialchars(strip_tags($this->subdistrict));
    $this->district=htmlspecialchars(strip_tags($this->district));
    $this->city=htmlspecialchars(strip_tags($this->city));
    $this->detail=htmlspecialchars(strip_tags($this->detail));
    $this->specific=htmlspecialchars(strip_tags($this->specific));
    $this->status=htmlspecialchars(strip_tags($this->status));
    $this->type_id=htmlspecialchars(strip_tags($this->type_id));
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->reg_date=htmlspecialchars(strip_tags($this->reg_date));


    // bind values
    $stmt->bindParam(":id", $this->id);
    $stmt->bindParam(":pname", $this->pname);
    $stmt->bindParam(":fname", $this->fname);
    $stmt->bindParam(":lname", $this->lname);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":age", $this->age);
    $stmt->bindParam(":place", $this->place);
    $stmt->bindParam(":subdistrict", $this->subdistrict);
    $stmt->bindParam(":district", $this->district);
    $stmt->bindParam(":city", $this->city);
    $stmt->bindParam(":detail", $this->detail);
    $stmt->bindParam(":specific", $this->specific);
    $stmt->bindParam(":status", $this->status);
    $stmt->bindParam(":type_id",$this->type_id);
    $stmt->bindParam(":guest_id",$this->guest_id);
    $stmt->bindParam(":reg_date",$this->reg_date);

    if($stmt->execute()){
        return true;
    }

    return false;
   }

  function emailExists(){

    $query = "SELECT guest_id, guest_name, guest_pass, guest_email
    FROM  $this->table_name
    WHERE guest_email = ?
    LIMIT 0,1";

    // prepare the query
    $stmt = $this->connection->prepare($query);
    $this->guest_email=htmlspecialchars(strip_tags($this->guest_email));
    $stmt->bindParam(1, $this->guest_email);

    $stmt->execute();
    $num = $stmt->rowCount();

  if($num>0){
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->guest_id = $row['guest_id'];
    $this->guest_name = $row['guest_name'];
    $this->guest_email = $row['guest_email'];
    $this->guest_pass = $row['guest_pass'];
    return true;
  }
  return false;
}

  function update(){

                  $query = "UPDATE $this->table_name
                  SET pname = :pname,
                  fname = :fname,
                  lname = :lname,
                  gender = :gender,
                  age = :age,
                  place = :place,
                  subdistrict = :subdistrict,
                  district = :district,
                  city = :city,
                  detail = :detail,
                  specific = :specific,
                  status = :status,
                  type_id = :type_id,
                  guest_id = :guest_id,
                  reg_date = NOW(),
                  WHERE
                id = :id";


      // prepare query statement
      $stmt = $this->connection->prepare($query);

      // sanitize
      $this->id=htmlspecialchars(strip_tags($this->id));
      $this->pname=htmlspecialchars(strip_tags($this->pname));
      $this->fname=htmlspecialchars(strip_tags($this->fname));
      $this->lname=htmlspecialchars(strip_tags($this->lname));
      $this->gender=htmlspecialchars(strip_tags($this->gender));
      $this->age=htmlspecialchars(strip_tags($this->age));
      $this->place=htmlspecialchars(strip_tags($this->place));
      $this->subdistrict=htmlspecialchars(strip_tags($this->subdistrict));
      $this->district=htmlspecialchars(strip_tags($this->district));
      $this->city=htmlspecialchars(strip_tags($this->city));
      $this->detail=htmlspecialchars(strip_tags($this->detail));
      $this->specific=htmlspecialchars(strip_tags($this->specific));
      $this->status=htmlspecialchars(strip_tags($this->status));
      $this->type_id=htmlspecialchars(strip_tags($this->type_id));
      $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
      $this->reg_date=htmlspecialchars(strip_tags($this->reg_date));

      // bind new values
      $stmt->bindParam(':id', $this->id);
      $stmt->bindParam(':pname', $this->pname);
      $stmt->bindParam(':fname', $this->fname);
      $stmt->bindParam(':lname', $this->lname);
      $stmt->bindParam(':gender', $this->gender);
      $stmt->bindParam(':age', intval($this->age));
      $stmt->bindParam(':place', $this->place);
      $stmt->bindParam(':subdistrict', $this->subdistrict);
      $stmt->bindParam(':district', $this->district);
      $stmt->bindParam(':city', $this->city);
      $stmt->bindParam(':detail', $this->detail);
      $stmt->bindParam(':specific', $this->specific);
      $stmt->bindParam(':status', intval($this->status));
      $stmt->bindParam(':type_id', intval($this->type_id));
      $stmt->bindParam(':guest_id', intval($this->guest_id));
      $stmt->bindParam(':reg_date', $this->reg_date);

      // execute the query
      if($stmt->execute()){
              return true;
          }

          return false;
      }

      // search products
function search($keywords){

    // select all query


    $query = "SELECT fname,lname,detail,reg_date FROM
                 . $this->table_name
            WHERE
                fname LIKE ? OR lname LIKE ? OR detail LIKE ?
            ORDER BY
                reg_date DESC";

    // prepare query statement
    $stmt = $this->connection->prepare($query);

    // sanitize
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // bind
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);

    $stmt->execute();

    return $stmt;
  }
}
?>
