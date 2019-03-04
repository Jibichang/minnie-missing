<?php
include('../libs/THSplitLib/THSplitLib/segment.php');
// include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'api/libs/THSplitLib/THSplitLib/segment.php');
class MissingPersonsIR{
  private $connection;
  private $table_name = "peoplelost";
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

  public function __construct($connection){
    $this->connection = $connection;
  }

  public function searchIR($document, $query){
    $this->preProcess($document);
    $this->calIDF();
    $this->calQuery($query);
    // $this->calIR();
    return $this->calIR();
  }

  function read(){
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->connection->prepare($query);
    $stmt-> execute();
    // $count_doc = $stmt->rowCount();
    return $stmt;
  }

  function cutWord($detail)
  {
    $segment = new Segment();
    $result = $segment->get_segment_array($detail);

    return $result;
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
      array_push($this->doc, $row["detail_etc"]); // detail (doc)
      $this->word_all = $segment-> get_segment_array($row["detail_etc"]); //word
    }
    $this->word_unique = array_keys(array_count_values($this->word_all)); // word unique
    // stop word
    $diff = array_diff($this->word_unique,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);
    $this->word_unique = array_values($diff);
    $this->count_word_all = count($this->word_unique); // count word unique

    return $this->doc;

  }

  function clearIDF(){
    ///delete data in table idf
    $queryDel = "DELETE FROM invert";
    $stmtDel = $this->connection->prepare($queryDel);
    if($stmtDel->execute()){}

      //INSERT to phpmyadmin///
      for ($i=0; $i < $this->count_word_all; $i++) {
        $term = $this->word_unique[$i];
        $idf = $this->idf_value[$i];
        $queryTerm = "INSERT INTO `invert`(`term`,`idf`) VALUES ('$term','$idf')";
        $stmtTerm = $this->connection->prepare($queryTerm);
        $stmtTerm->bindParam(":term", $term);
        $stmtTerm->bindParam(":term", $idf);
        if($stmtTerm->execute()){}
        }
      }

      function calIDF(){
        $segment = new Segment();

        $result = array();$result_freq = array();
        $freq = array();
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
            $count_n;
            $freq[$doc_key] = array_unique($freq_temp);
            $result_freq[$doc_key] = array_count_values($freq[$doc_key]);
            foreach ($result_freq[$doc_key] as $key => $value) {
              $count_n[$key] += count($value); // valid value tf [true]
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
          // ///delete data in table idf
          // $queryDel = "DELETE FROM invert";
          // $stmtDel = $this->connection->prepare($queryDel);
          // if($stmtDel->execute()){}
          //
          // //INSERT to phpmyadmin///
          // for ($i=0; $i < $this->count_word_all; $i++) {
          //   $term = $this->word_unique[$i];
          //   $idf = $this->idf_value[$i];
          //   $queryTerm = "INSERT INTO `invert`(`term`,`idf`) VALUES ('$term','$idf')";
          //   $stmtTerm = $this->connection->prepare($queryTerm);
          //   $stmtTerm->bindParam(":term", $term);
          //   $stmtTerm->bindParam(":term", $idf);
          //   if($stmtTerm->execute()){}
          // }
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
              if ($this->cut_query[$key] > 0) {
                // weight
                $weight = $this->idf[$key]*$value;
                // sim = frequency query* ( weight / lenght)
                $sim_value += $this->cut_query[$key]*($weight/$lengh[$freq_key]);
              }
              // } ///and this line
            }
            $sim[$freq_key] = $sim_value;
          }
          arsort($sim); // sort doc
          $sim_result = array();

          $missing_arr=array();
          $missing_arr["records"]=array();
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
              array_push($missing_arr["records"], $missing_item);
              // array_push($sim_result, $row["detail_etc"]); // detail (doc)
            }

          }
          return json_encode($missing_arr,JSON_UNESCAPED_UNICODE);
          // return $sim_result;
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
          reg_date = NOW(),
          feedback_id = :feedback_id";

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
          $this->feedback_id=htmlspecialchars(strip_tags($this->feedback_id));

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
          $stmt->bindParam(":feedback_id", $this->feedback_id);
        }

      }
      ?>
