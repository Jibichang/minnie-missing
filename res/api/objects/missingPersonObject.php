<?php
class MissingPersons{
  private $connection;
  private $table_name = "personlost";


  public $plost_id;
  public $pname;
  public $fname;
  public $lname;
  public $gender;
  public $age;
  public $place;
  public $subdistrict;
  public $district;
  public $city;
  public $height;
  public $weight;
  public $shape;
  public $hairtype;
  public $haircolor;
  public $skintone;
  public $upperwaist;
  public $uppercolor;
  public $lowerwaist;
  public $lowercolor;
  public $detail_etc;
  public $special;
  public $type_id;
  public $guest_id;
  public $status;
  public $reg_date;

  public function __construct($connection){
    $this->connection = $connection;

  }

  function read(){

     $query = "SELECT *  FROM " . $this->table_name ;
     $stmt = $this->connection->prepare($query);
     $stmt->execute();

     return $stmt;
   }

   function delete(){

       // delete query
       $query = "DELETE FROM " . $this->table_name . " WHERE plost_id = ?";

       // prepare query
       $stmt = $this->connection->prepare($query);

       // sanitize
       $this->id=htmlspecialchars(strip_tags($this->plost_id));

       // bind id of record to delete
       $stmt->bindParam(1, $this->plost_id);

       // execute query
       if($stmt->execute()){
           return true;
       }
       return false;
     }

     function create(){

       $query = "INSERT INTO
                 " . $this->table_name . "
             SET
                 plost_id = :plost_id,
                 pname = :pname,
                 fname = :fname,
                 lname = :lname,
                 gender = :gender,
                 age = :age,
                 place = :place,
                 subdistrict = :subdistrict,
                 district = :district,
                 city = :city,
                 height = :height,
                 weight = :weight,
                 shape = :shape,
                 hairtype = :hairtype,
                 haircolor = :haircolor,
                 skintone = :skintone,
                 upperwaist = :upperwaist,
                 uppercolor = :uppercolor,
                 lowerwaist = :lowerwaist,
                 lowercolor = :lowercolor,
                 detail_etc = :detail_etc,
                 special = :special,
                 type_id = :type_id,
                 guest_id = :guest_id,
                 status = :status,
                 reg_date = :reg_date";

     // prepare query
     $stmt = $this->connection->prepare($query);

     // sanitize
    $this->plost_id=htmlspecialchars(strip_tags($this->plost_id));
    $this->pname=htmlspecialchars(strip_tags($this->pname));
    $this->fname=htmlspecialchars(strip_tags($this->fname));
    $this->lname=htmlspecialchars(strip_tags($this->lname));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->age=htmlspecialchars(strip_tags($this->age));
    $this->place=htmlspecialchars(strip_tags($this->place));
    $this->subdistrict=htmlspecialchars(strip_tags($this->subdistrict));
    $this->district=htmlspecialchars(strip_tags($this->district));
    $this->city=htmlspecialchars(strip_tags($this->city));
    $this->height=htmlspecialchars(strip_tags($this->height));
    $this->weight=htmlspecialchars(strip_tags($this->weight));
    $this->shape=htmlspecialchars(strip_tags($this->shape));
    $this->hairtype=htmlspecialchars(strip_tags($this->hairtype));
    $this->haircolor=htmlspecialchars(strip_tags($this->haircolor));
    $this->skintone=htmlspecialchars(strip_tags($this->skintone));
    $this->upperwaist=htmlspecialchars(strip_tags($this->upperwaist));
    $this->uppercolor=htmlspecialchars(strip_tags($this->uppercolor));
    $this->lowerwaist=htmlspecialchars(strip_tags($this->lowerwaist));
    $this->lowercolor=htmlspecialchars(strip_tags($this->lowercolor));
    $this->detail_etc=htmlspecialchars(strip_tags($this->detail_etc));
    $this->special=htmlspecialchars(strip_tags($this->special));
    $this->type_id=htmlspecialchars(strip_tags($this->type_id));
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->status=htmlspecialchars(strip_tags($this->status));
    $this->reg_date=htmlspecialchars(strip_tags($this->reg_date));

     // bind values
     $stmt->bindParam(":plost_id", $this->plost_id);
     $stmt->bindParam(":pname", $this->pname);
     $stmt->bindParam(":fname", $this->fname);
     $stmt->bindParam(":lname", $this->lname);
     $stmt->bindParam(":gender", $this->gender);
     $stmt->bindParam(":age", $this->age);
     $stmt->bindParam(":place", $this->place);
     $stmt->bindParam(":subdistrict", $this->subdistrict);
     $stmt->bindParam(":district", $this->district);
     $stmt->bindParam(":city", $this->city);
     $stmt->bindParam(":height", $this->height);
     $stmt->bindParam(":weight", $this->weight);
     $stmt->bindParam(":shape", $this->shape);
     $stmt->bindParam(":hairtype", $this->hairtype);
     $stmt->bindParam(":haircolor", $this->haircolor);
     $stmt->bindParam(":skintone", $this->skintone);
     $stmt->bindParam(":upperwaist", $this->upperwaist);
     $stmt->bindParam(":uppercolor", $this->upperwaist);
     $stmt->bindParam(":lowerwaist", $this->lowerwaist);
     $stmt->bindParam(":lowercolor", $this->lowerwaist);
     $stmt->bindParam(":detail_etc", $this->detail_etc);
     $stmt->bindParam(":special", $this->special);
     $stmt->bindParam(":type_id",$this->type_id);
     $stmt->bindParam(":guest_id",$this->guest_id);
     $stmt->bindParam(":status", $this->status);
     $stmt->bindParam(":reg_date",$this->reg_date);

     // execute query
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
                         height = :height,
                         weight = :weight,
                         shape = :shape,
                         hairtype = :hairtype,
                         haircolor = :haircolor,
                         skintone = :skintone,
                         upperwaist = :upperwaist,
                         uppercolor = :uppercolor,
                         lowerwaist = :lowerwaist,
                         lowercolor = :lowercolor,
                         detail_etc = :detail_etc,
                         special = :special,
                         type_id = :type_id,
                         guest_id = :guest_id,
                         status = :status,
                         reg_date = :reg_date
                        WHERE
                      plost_id = :plost_id";

                      $stmt = $this->connection->prepare($query);

                      // sanitize
                     $this->plost_id=htmlspecialchars(strip_tags($this->plost_id));
                     $this->pname=htmlspecialchars(strip_tags($this->pname));
                     $this->fname=htmlspecialchars(strip_tags($this->fname));
                     $this->lname=htmlspecialchars(strip_tags($this->lname));
                     $this->gender=htmlspecialchars(strip_tags($this->gender));
                     $this->age=htmlspecialchars(strip_tags($this->age));
                     $this->place=htmlspecialchars(strip_tags($this->place));
                     $this->subdistrict=htmlspecialchars(strip_tags($this->subdistrict));
                     $this->district=htmlspecialchars(strip_tags($this->district));
                     $this->city=htmlspecialchars(strip_tags($this->city));
                     $this->height=htmlspecialchars(strip_tags($this->height));
                     $this->weight=htmlspecialchars(strip_tags($this->weight));
                     $this->shape=htmlspecialchars(strip_tags($this->shape));
                     $this->hairtype=htmlspecialchars(strip_tags($this->hairtype));
                     $this->haircolor=htmlspecialchars(strip_tags($this->haircolor));
                     $this->skintone=htmlspecialchars(strip_tags($this->skintone));
                     $this->upperwaist=htmlspecialchars(strip_tags($this->upperwaist));
                     $this->uppercolor=htmlspecialchars(strip_tags($this->uppercolor));
                     $this->lowerwaist=htmlspecialchars(strip_tags($this->lowerwaist));
                     $this->lowercolor=htmlspecialchars(strip_tags($this->lowercolor));
                     $this->detail_etc=htmlspecialchars(strip_tags($this->detail_etc));
                     $this->special=htmlspecialchars(strip_tags($this->special));
                     $this->type_id=htmlspecialchars(strip_tags($this->type_id));
                     $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
                     $this->status=htmlspecialchars(strip_tags($this->status));
                     $this->reg_date=htmlspecialchars(strip_tags($this->reg_date));

                      // bind values
                      $stmt->bindParam(":plost_id", $this->plost_id);
                      $stmt->bindParam(":pname", $this->pname);
                      $stmt->bindParam(":fname", $this->fname);
                      $stmt->bindParam(":lname", $this->lname);
                      $stmt->bindParam(":gender", $this->gender);
                      $stmt->bindParam(":age", $this->age);
                      $stmt->bindParam(":place", $this->place);
                      $stmt->bindParam(":subdistrict", $this->subdistrict);
                      $stmt->bindParam(":district", $this->district);
                      $stmt->bindParam(":city", $this->city);
                      $stmt->bindParam(":height", $this->height);
                      $stmt->bindParam(":weight", $this->weight);
                      $stmt->bindParam(":shape", $this->shape);
                      $stmt->bindParam(":hairtype", $this->hairtype);
                      $stmt->bindParam(":haircolor", $this->haircolor);
                      $stmt->bindParam(":skintone", $this->skintone);
                      $stmt->bindParam(":upperwaist", $this->upperwaist);
                      $stmt->bindParam(":uppercolor", $this->upperwaist);
                      $stmt->bindParam(":lowerwaist", $this->lowerwaist);
                      $stmt->bindParam(":lowercolor", $this->lowerwaist);
                      $stmt->bindParam(":detail_etc", $this->detail_etc);
                      $stmt->bindParam(":special", $this->special);
                      $stmt->bindParam(":type_id",$this->type_id);
                      $stmt->bindParam(":guest_id",$this->guest_id);
                      $stmt->bindParam(":status", $this->status);
                      $stmt->bindParam(":reg_date",$this->reg_date);

                      // execute query
                      if($stmt->execute()){
                          return true;
                      }

                      return false;
      }

      // search products
function search(){
  $query = "SELECT * FROM
               . $this->table_name
          WHERE
      fname LIKE :fname OR lname LIKE :lname OR gender LIKE :gender OR city LIKE :city OR height LIKE :height OR shape LIKE :shape
      OR hairtype LIKE :hairtype OR haircolor LIKE :haircolor OR skintone LIKE :skintone OR type_id LIKE :type_id OR status LIKE :status
  ORDER BY
      fname DESC";
      // prepare query statement
      $stmt = $this->connection->prepare($query);
  // sanitize
  $this->fname=htmlspecialchars(strip_tags($this->fname));
  $this->lname=htmlspecialchars(strip_tags($this->lname));
  $this->gender=htmlspecialchars(strip_tags($this->gender));
  $this->city=htmlspecialchars(strip_tags($this->city));
  $this->height=htmlspecialchars(strip_tags($this->height));
  $this->shape=htmlspecialchars(strip_tags($this->shape));
  $this->hairtype=htmlspecialchars(strip_tags($this->hairtype));
  $this->haircolor=htmlspecialchars(strip_tags($this->haircolor));
  $this->skintone=htmlspecialchars(strip_tags($this->skintone));
  $this->type_id=htmlspecialchars(strip_tags($this->type_id));
  $this->status=htmlspecialchars(strip_tags($this->status));
  //$this->type=htmlspecialchars(strip_tags($this->type));
  //$this->missing_person=htmlspecialchars(strip_tags($this->missing_person));
  $this->fname = "%{$this->fname}%";
  $this->lname = "%{$this->lname}%";
  $this->gender = "%{$this->gender}%";
  $this->city = "%{$this->city}%";
  $this->height = "%{$this->height}%";
  $this->shape = "%{$this->shape}%";
  $this->hairtype = "%{$this->hairtype}%";
  $this->haircolor = "%{$this->haircolor}%";
  $this->skintone = "%{$this->skintone}%";
  $this->type_id = "%{$this->type_id}%";
  $this->status= "%{$this->status}%";
  //$this->lname = "%{$this->type}%";
  //$this->missing_person = "%{$this->missing_person}%";
  // bind
  $stmt->bindParam(":fname", $this->fname);
  $stmt->bindParam(":lname", $this->lname);
  $stmt->bindParam(":gender", $this->gender);
  $stmt->bindParam(":city", $this->city);
  $stmt->bindParam(":height", $this->height);
  $stmt->bindParam(":shape", $this->shape);
  $stmt->bindParam(":hairtype", $this->hairtype);
  $stmt->bindParam(":haircolor", $this->haircolor);
  $stmt->bindParam(":skintone", $this->skintone);
  $stmt->bindParam(":type_id", $this->type_id);
  $stmt->bindParam(":status", $this->status);
  //$stmt->bindParam(":type", $this->type);
  //$stmt->bindParam(":missing_person", $this->missing_person);
  //$stmt->bindParam(1, $keywords);
  //$stmt->bindParam(2, $keywords);
  //$stmt->bindParam(3, $keywords);
  $stmt->execute();
  return $stmt;
  }

}
  function read_one($keywords){

      $query = "SELECT * FROM
                   . $this->table_name
              WHERE
                  plost_id =$keywords
                  ORDER BY plost_id DESC";

      // prepare query statement
      $stmt = $this->connection->prepare($query);

      $stmt->execute();

      return $stmt;
}

      function search_one(){



    }
?>
