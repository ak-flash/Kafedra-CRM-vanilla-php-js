<?php
class Quiz{
 
    // database connection and table name
    private $conn;
    private $table_name = "quiz";
 
    // object properties
    public $id;
	public $delete_q;
    public $question;
    public $good;
    public $bad1;
    public $bad2;
    public $bad3;
    public $tag_topic1;public $tag_topic2;
    public $tag_lech;public $tag_ped;public $tag_mbf3;
    public $tag_mbf4;public $tag_stom;public $tag_mpd;
    public $tag_lecheng;public $tag_stomeng;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

        // count of all questions
function questions_count($filter, $filter_value){
            if($filter==0){
                // select all query
                $query = "SELECT COUNT(*) FROM " . $this->table_name. " WHERE `delete_q`='0'";
            }
            else {
                $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE `delete_q`='0' AND `".$filter."`='".$filter_value."'";
                }
                // prepare query statement
                $stmt = $this->conn->prepare($query);
            
                // execute query
                $stmt->execute();
            
                return $stmt;
            }
    // read all
    function read($items_per_page, $page, $filter, $filter_value){
    
        $offset=(int)$items_per_page*(int)$page-(int)$items_per_page;

    if($filter==0){
        // select all query
        $query = "SELECT * FROM " . $this->table_name ." WHERE `delete_q`='0' ORDER BY `id` DESC LIMIT " . $offset . "," . $items_per_page;
    }
    else {
        $query = "SELECT * FROM " . $this->table_name . " WHERE `delete_q`='0' AND `".$filter."`='".$filter_value."' ORDER BY `id` DESC LIMIT " . $offset . "," . $items_per_page;
        }
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
    
        return $stmt;
    }

    // get single data
    function read_single($id){
    
        // select all query
        $query = "SELECT * FROM " . $this->table_name . " WHERE id= '".$id."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        return $stmt;
    }

    // create 
    function create(){
    
        if($this->isAlreadyExist()){
            return false;
        }
        
        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ." 
                        (`question`, `good`, `bad1`, `bad2`, `bad3`, `tag_topic1`, `tag_topic2`, `tag_lech`, `tag_ped`, `tag_mbf3`, `tag_mbf4`, `tag_stom`, `tag_mpd`, `tag_lecheng`, `tag_stomeng`)
                  VALUES
                        ('".$this->question."', '".$this->good."', '".$this->bad1."', '".$this->bad2."', '".$this->bad3."', '".$this->tag_topic1."', '".$this->tag_topic2."', 
                        '".$this->tag_lech."', '".$this->tag_ped."', '".$this->tag_mbf3."', '".$this->tag_mbf4."', '".$this->tag_stom."', '".$this->tag_mpd."', '".$this->tag_lecheng."', '".$this->tag_stomeng."')";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();
            return true;
        }

        return false;
    }

    // update 
    function update(){
    
        // query to insert record
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                question='".$this->question."', good='".$this->good."', bad1='".$this->bad1."', bad2='".$this->bad2."', bad3='".$this->bad3."', 
                tag_topic1='".$this->tag_topic1."', tag_topic2='".$this->tag_topic2."', tag_lech='".$this->tag_lech."', tag_lecheng='".$this->tag_lecheng."', tag_ped='".$this->tag_ped."', 
                tag_mbf3='".$this->tag_mbf3."', tag_mbf4='".$this->tag_mbf4."', tag_stom='".$this->tag_stom."', 
                tag_stomeng='".$this->tag_stomeng."', tag_mpd='".$this->tag_mpd."'
                WHERE
                    id='".$this->id."'";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    // delete
    function delete(){
        
$query = "UPDATE
                    " . $this->table_name . "
                SET
                delete_q='1'
                WHERE
                    id='".$this->id."'";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function isAlreadyExist(){
        $query = "SELECT id
            FROM
                " . $this->table_name . " 
            WHERE
                question='".$this->question."'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return true;
        }
        else{
            return false;
        }
    }
}

?>