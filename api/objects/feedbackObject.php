<?php
class Feedback{
  private $connection;
  private $table_name = "feedback";

  public $feedback_id;
  public $record;


  public function __construct($connection){
    $this->connection = $connection;
  }

  function create(){

 // query to insert record
   $query = "INSERT INTO
             " . $this->table_name . "
         SET
             feedback_id = :feedback_id,
             record = :record";

 // prepare query
 $stmt = $this->connection->prepare($query);

 // sanitize
 $this->feedback_id=htmlspecialchars(strip_tags($this->feedback_id));
 $this->record=htmlspecialchars(strip_tags($this->record));

 // bind values
 $stmt->bindParam(":feedback_id", $this->feedback_id);
  $stmt->bindParam(":record", $this->record);
}


  public function read(){
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->connection-> prepare($query);

    $stmt-> execute();
    return $stmt;
    }

    function update(){

      $query = "INSERT INTO
                " . $this->table_name . "
            SET
                feedback_id = :feedback_id,
                record = :record";

      $stmt = $this->connection->prepare($query);

      $this->feedback_id=htmlspecialchars(strip_tags($this->feedback_id));
      $this->record=htmlspecialchars(strip_tags($this->record));

      $stmt->bindParam(":feedback_id", $this->feedback_id);
      $stmt->bindParam(":record", $this->record);


      if($stmt->execute()){
        return true;
        echo "jjjj";
      }
      return false;
    }

  }
?>
