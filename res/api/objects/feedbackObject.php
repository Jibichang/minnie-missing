<?php
class Feedback{
  private $connection;
  private $table_name = "feedback";

  public $feedback_id;
  public $guest_id;
  public $id;


  public function __construct($connection){
    $this->connection = $connection;
  }


  function create(){


     // query to insert record
       $query = "INSERT INTO
                 " . $this->table_name . "
             SET
                 feedback_id = :feedback_id,
                 guest_id = :guest_id,
                 id = :id";

     // prepare query
     $stmt = $this->connection->prepare($query);

     // sanitize
    $this->feedback_id=htmlspecialchars(strip_tags($this->feedback_id));
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->id=htmlspecialchars(strip_tags($this->id));

     // bind values
     $stmt->bindParam(":feedback_id", $this->feedback_id);
     $stmt->bindParam(":guest_id", $this->guest_id);
     $stmt->bindParam(":id", $this->id);
     // execute query
     if($stmt->execute()){
         return true;
     }

     return false;
    }


public function read(){
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->connection-> prepare($query);

    $stmt-> execute();
    return $stmt;
    }


    function update(){

          $query = "UPDATE $this->table_name
          SET guest_id = :guest_id,
              id = :id,
          WHERE
        feedback_id = :feedback_id";


      $stmt = $this->connection->prepare($query);

      $this->feedback_id=htmlspecialchars(strip_tags($this->feedback_id));
      $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
      $this->id=htmlspecialchars(strip_tags($this->id));

      $stmt->bindParam(":feedback_id", $this->feedback_id);
      $stmt->bindParam(":guest_id", $this->guest_id);
      $stmt->bindParam(":id", $this->id);

      // execute the query
          if($stmt->execute()){
              return true;
          }

          return false;
  }

}
?>
