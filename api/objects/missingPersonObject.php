<?php
class MissingPersons{
  private $connection;
  private $table_name = "peoplelost";


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

  private $count_doc = 0;
  private $word_all = array();
  private $count_word_all = 0;
  private $word_per_doc = array();
  private $count_word_per_doc = array();
  private $cut_query = array();
  private $doc = array();
  private $word_unique = array();
  private $idf_value = array();
  private $idf =array();
  private $freq_doc = array();

  private $sim_result_return = array();

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
    $query = "SELECT * FROM $this->table_name
    WHERE IF(gender = :gender AND status = :status ,
      IF(fname LIKE :fname OR lname LIKE :lname ,1,0) ,0)";
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
      // $this->gender = "%{$this->gender}%";
      // $this->city = "%{$this->city}%";
      // $this->height = "%{$this->height}%";
      // $this->shape = "%{$this->shape}%";
      // $this->hairtype = "%{$this->hairtype}%";
      // $this->haircolor = "%{$this->haircolor}%";
      // $this->skintone = "%{$this->skintone}%";
      // $this->type_id = "%{$this->type_id}%";
      // $this->status= "%{$this->status}%";
      //$this->lname = "%{$this->type}%";
      //$this->missing_person = "%{$this->missing_person}%";
      // bind
      $stmt->bindParam(":fname", $this->fname);
      $stmt->bindParam(":lname", $this->lname);
      $stmt->bindParam(":gender", $this->gender);
      // $stmt->bindParam(":city", $this->city);
      // $stmt->bindParam(":height", $this->height);
      // $stmt->bindParam(":shape", $this->shape);
      // $stmt->bindParam(":hairtype", $this->hairtype);
      // $stmt->bindParam(":haircolor", $this->haircolor);
      // $stmt->bindParam(":skintone", $this->skintone);
      // $stmt->bindParam(":type_id", $this->type_id);
      $stmt->bindParam(":status", $this->status);

      //$stmt->bindParam(":type", $this->type);
      //$stmt->bindParam(":missing_person", $this->missing_person);
      //$stmt->bindParam(1, $keywords);
      //$stmt->bindParam(2, $keywords);
      //$stmt->bindParam(3, $keywords);
      $stmt->execute();



      return $stmt;
    }
    public function searchIR($document, $query){
      $this->preProcess($document);
      $this->calIDF();
      $this->calQuery($query);
      // $this->calIR();
      return $this->calIR();
    }
    function preProcess($stmt){
      $segment = new Segment();

      // $query = "SELECT * FROM " . $this->table_name;
      // $stmt2 = $this->connection->prepare($query);
      // $stmt2-> execute();

      $this->count_doc = 0;
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
        $this->count_doc += 1; // N doc
        $str = $row["detail_etc"]." ".$row["city"]." ".$row["skintone"]." ".$row["hairtype"];
        array_push($this->doc, $str); // detail (doc)
        $this->word_all = $segment-> get_segment_array($str); //word
      }
      $this->word_unique = array_keys(array_count_values($this->word_all)); // word unique
      // stop word
      $diff = array_diff($this->word_unique,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);
      $this->word_unique = array_values($diff);
      $this->count_word_all = count($this->word_unique); // count word unique

      return $this->doc;

    }


    function calIDF(){
      $segment = new Segment();

      $result = array();$result_freq = array();
      $freq = array(); $count_n = array();
      foreach ($this->doc as $doc_key => $doc_value) { // N doc
        $word_doc = array(); $freq_temp = array();
        $cut_doc = array();
        $word_doc = $segment-> get_segment_array($this->doc[$doc_key]); //cut term per doc
        $diff = array_diff($word_doc,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);

        $sm = new Segment();
        $cut_doc = $sm-> get_segment_array($this->doc[$doc_key]); //cut term per doc
        $cut_doc = array_diff($cut_doc,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);

        // have term in doc?
        $temp = array_intersect($word_doc, $this->word_unique);
        $freq_temp = array_intersect($cut_doc, $this->word_unique);
        $this->freq_doc[$doc_key] = array_count_values(
          array_intersect($cut_doc, $this->word_unique));
          // query and cut doc
          // $this->freq_doc[$doc_key] = array_intersect($cut_doc, array_keys($this->cut_query));

          $freq[$doc_key] = array_unique($freq_temp);
          $result_freq[$doc_key] = array_count_values($freq[$doc_key]);
          //error_reporting(error_reporting() & ~E_NOTICE); // hide error nextime pls come to fix it
          foreach ($result_freq[$doc_key] as $key_x => $value_x) {
            if (empty($count_n[$key_x])) {
              $count_n[$key_x] = 0;
            }
            $count_n[$key_x] += $value_x; // valid value tf [true]
          }
          // $freq[$doc_key] = array_count_values($cut_doc);
          // $temp_freq = array_intersect($freq[$doc_key], $this->word_unique);
          // $this->freq_doc[$doc_key] = $cut_doc;
          // number of term in doc // term => frequency
          $result = array_count_values($temp);
          $this->idf_value = array_values($count_n);
          foreach ($this->idf_value as $key => $value) { // calculate idf
            $this->idf_value[$key] = round((log10(($this->count_doc)/($this->idf_value[$key]))),4);
          }
          // same value term unique before compare $countx = count($result);
        }
        return $this->doc;
      }

      function calQuery($input_detail){
        $segment = new Segment();
        $cutInput = array();

        $data = $input_detail;
        $cutInput = $segment -> get_segment_array($data);
        $diff = array_diff($cutInput,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);
        $this->cut_query = array_count_values($diff);
        // $this->cut_query = array_values($diff);
        // return $this->cut_query;
      }

      function calIR(){
        $segment = new Segment();
        $query = "SELECT * FROM invert";
        $stmt = $this->connection->prepare($query);
        $stmt-> execute();

        $lengh = array();

        for ($i=0; $i < $this->count_word_all; $i++) {
          $term = $this->word_unique[$i];
          $idf = $this->idf_value[$i];
          $this->idf[$term] = $idf;
        }
        $sim = array();
        $weight = 0;
        foreach ($this->freq_doc as $freq_key => $freq_value) {
          $sum = 0;
          foreach ($freq_value as $key => $value) {
            // weight = idf * tf
            $weight = $this->idf[$key]*$value;
            $sum += $weight; // length
          }
          $lengh[$freq_key] = $sum;
          $sim_value = 0;
          foreach ($freq_value as $key => $value) {
            // if ($key == $freq_value[$key]) { ///mark error if wrong value pls hide this line
            if(!empty($this->cut_query[$key])){
              if ($this->cut_query[$key] > 0) {
                // weight
                $weight = $this->idf[$key]*$value;
                // sim = frequency query* ( weight / lenght)
                $sim_value += $this->cut_query[$key]*($weight/$lengh[$freq_key]);
              }
            } else {
              $this->cut_query[$key] = 0;
            }
            // if ($this->cut_query[$key] > 0) {
            //   // weight
            //   $weight = $this->idf[$key]*$value;
            //   // sim = frequency query* ( weight / lenght)
            //   $sim_value += $this->cut_query[$key]*($weight/$lengh[$freq_key]);
            // }
            // } ///and this line
          }
          $sim[$freq_key] = $sim_value;
        }
        arsort($sim); // sort doc
        $sim_result = array();

        $missing_arr=array();
        $missing_arr["body"]=array();
        foreach ($sim as $key => $value) {
          $key_plus = $key + 1;
          $query = "SELECT * FROM $this->table_name WHERE plost_id = '$key_plus'";
          $stmt = $this->connection->prepare($query);
          $stmt-> execute();

          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
          {
            extract($row);
            $missing_item = array(
              "fname"=> $fname,
              "lname"=> $lname,
              "gender"=>$gender,
              "city"=> $city,
              "height"=> $height,
              "shape"=> $shape,
              "hairtype"=>$hairtype,
              "haircolor"=> $haircolor,
              "skintone"=> $skintone,
              "detail_etc"=> $detail_etc,
              "type_id"=>$type_id,
              "status"=> $status,
              "reg_date"=> $reg_date
            );
            array_push($missing_arr["body"], $missing_item);
            // array_push($sim_result, $row["detail_etc"]); // detail (doc)
          }

        }
        return json_encode($missing_arr,JSON_UNESCAPED_UNICODE);
        // return $sim_result;
      }

    }

    ?>
