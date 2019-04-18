<?php
class Comment{
 
    //database table name
    private $table_name = "comments_tbl";
 
    // object properties
    public $id;
    public $film_id;
    public $comment;
    public $user_ip;
    public $posted_at;

    
    public function __construct($db){
        $this->conn = $db;
    }
    
    //function to fetch comments from db
    function read(){
        $query = "SELECT *
    FROM
        " . $this->table_name . "
        ORDER BY
        posted_at DESC";  
        
        //prepare statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    // function to create a comment
function create(){
 
    // query to insert record
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                film_id=:film_id, episode_id=:episode_id, comment=:comment, user_ip=:user_ip";
 
    // prepare query
    $stmt = $this->conn->prepare($query);
 
    // sanitize
    $this->film_id=htmlspecialchars(strip_tags($this->film_id));
    $this->episode_id=htmlspecialchars(strip_tags($this->episode_id));
    $this->comment=htmlspecialchars(strip_tags($this->comment));
    $this->user_ip=htmlspecialchars(strip_tags($this->user_ip));
    //$this->posted_at=htmlspecialchars(strip_tags($this->posted_at));
 
    // bind values
    $stmt->bindParam(":film_id", $this->film_id);
    $stmt->bindParam(":episode_id", $this->film_id);
    $stmt->bindParam(":comment", $this->comment);
    $stmt->bindParam(":user_ip", $this->user_ip);
    //$stmt->bindParam(":posted_at", $this->posted_at);
 
    // execute query
    if($stmt->execute()){
        return true;
    }
 
    return false;
     
}
//function to fetch number of comments per movie
    function getCommentCount($episode_id){
        $query = "SELECT COUNT(comment) AS totalComments 
        FROM  " . $this->table_name . "
        WHERE episode_id = '".$episode_id."'";

                
        //prepare statement
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['totalComments'];
    }

}
?>