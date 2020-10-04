<?php

class Quiz {
 
    // database connection and table name
    private $conn;
    private $table_name = "quiz";
 
    // object properties
    public $id;
	public $deleted;
    public $question;
    public $good_answer;
    public $bad1_answer;
    public $bad2_answer;
    public $bad3_answer;
    public $topic_tags;
    public $faculty_tags;
    public $items_per_page;
    public $page;
    public $search_q;
    public $search_topic;
    public $already_exist_id;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

        // count of all questions
    function questions_count(){
            
                $query = "SELECT COUNT(*) FROM " . $this->table_name. " WHERE `deleted`='0'";
           
                // prepare query statement
                $stmt = $this->conn->prepare($query);

                // execute query
                $stmt->execute();
            
                return $stmt;
            }

    // read all
    function list($page = 1, $items_per_page = 10){
    
    $offset=(int)$items_per_page*(int)$page-(int)$items_per_page;
    
    // Total amount of questions
    if(!empty($this->search_q)) {
        $extra_query_count = "WHERE deleted = 0 AND question LIKE '%".$this->search_q."%'";
        $extra_query = "WHERE deleted = 0 AND question LIKE '%".$this->search_q."%'";
    } else {
        
        if(!empty($this->search_topic) && $this->search_topic != 0) {
            
            $extra_query_count = "LEFT JOIN quiz_topics ON quiz.id=quiz_topics.quiz_id WHERE deleted = 0 AND quiz_topics.topic_id = ".(int) $this->search_topic;
            
            $extra_query = "LEFT JOIN quiz_topics ON quiz.id=quiz_topics.quiz_id WHERE deleted = 0 AND quiz_topics.topic_id = ".(int) $this->search_topic;

        } else {
            $extra_query_count = "WHERE deleted = 0";
            $extra_query = "WHERE deleted = 0";
        }
        
    }
  
    $stmt_count = $this->conn->query("SELECT count(*) as count FROM " . $this->table_name." ".$extra_query_count);
    $row_count = $stmt_count->fetch(PDO::FETCH_ASSOC);

    $query = "SELECT quiz.id, question, good_answer, bad1_answer,bad2_answer,bad3_answer,quiz.updated_at,block,users.lastname FROM " . $this->table_name ." LEFT JOIN users ON users.id=quiz.user_id ".$extra_query." ORDER BY id DESC LIMIT " . (int) $offset . "," . (int) $items_per_page;
   
        // prepare query statement
        $stmt = $this->conn->prepare($query);
    
        // execute query
        $stmt->execute();
        
        $data_arr = array();
        $data_arr["quizzes"] = array();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
            //extract($row);
            $data_item=array(
                "id" => $row['id'],
                "question" => $row['question'],
                "good_answer" => $row['good_answer'],
                "bad1_answer" => $row['bad1_answer'],
                "bad2_answer" => $row['bad2_answer'],
                "bad3_answer" => $row['bad3_answer'],
                "author" => $row['lastname'],
                "updated_at" => date("H:i d/m/Y" ,strtotime($row['updated_at'])),
            );
            array_push($data_arr["quizzes"], $data_item);
                   
        }

        $data_arr["count"] = $row_count['count'];

        return json_encode($data_arr);
    }

    // get single data
    function show($id){
    
        // select all query
        $query = "SELECT * FROM " . $this->table_name . "
                    WHERE id = ? LIMIT 0,1";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        // execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $topic_tags = "";
        $query_extra = "SELECT topic_id FROM quiz_topics WHERE quiz_id = ".$this->id;
        $stmt_extra = $this->conn->prepare($query_extra);
        $stmt_extra->execute();
        if($stmt_extra->rowcount()>0) {
            while($row_extra = $stmt_extra->fetch(PDO::FETCH_ASSOC)){ 
                $topic_tags = $topic_tags.$row_extra['topic_id'].",";
            }
            
        } else {
            $topic_tags = "";
        }
        

        $data_arr = array(
            "id" => $row['id'],
            "question" => $row['question'],
            "good_answer" => $row['good_answer'],
            "bad1_answer" => $row['bad1_answer'],
            "bad2_answer" => $row['bad2_answer'],
            "bad3_answer" => $row['bad3_answer'],
            "topic_tags" => $topic_tags,
            "block" => $row['block'],
        );

            // Set block of edited element
            $this->conn->query("UPDATE " . $this->table_name . " SET block = 1 WHERE id = ".$this->id);
            
        return json_encode($data_arr);
    }

    // create 
    function create(){
    
        if($this->isAlreadyExist()){
            return false;
        }
        
        // query to insert record
        $query = "INSERT INTO  ". $this->table_name ." 
                        (question, good_answer, bad1_answer, bad2_answer, bad3_answer, user_id)
                  VALUES
                        (:question, :good_answer, :bad1_answer, :bad2_answer, :bad3_answer, :user_id)";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':question', $this->question);
        $stmt->bindParam(':good_answer', $this->good_answer);
        $stmt->bindParam(':bad1_answer', $this->bad1_answer);
        $stmt->bindParam(':bad2_answer', $this->bad2_answer);
        $stmt->bindParam(':bad3_answer', $this->bad3_answer);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        
        // execute query
        if($stmt->execute()){
            $this->id = $this->conn->lastInsertId();

            for ($i = 0; $i < count($this->topic_tags); $i++) {
                $this->conn->query("INSERT INTO  quiz_topics (quiz_id, topic_id)
                                    VALUES (".$this->id.", ".$this->topic_tags[$i].")");
            }

            return true;
        }

        return false;
    }

    // update 
    function update(){
        if($this->isAlreadyExist()){
            return false;
        }
        // query to insert record
        $query = "UPDATE " . $this->table_name . " 
                SET question= :question, good_answer = :good_answer, bad1_answer = :bad1_answer, bad2_answer = :bad2_answer, bad3_answer = :bad3_answer, user_id = :user_id
                WHERE id = :id";
    
        // prepare query
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindParam(':question', $this->question);
        $stmt->bindParam(':good_answer', $this->good_answer);
        $stmt->bindParam(':bad1_answer', $this->bad1_answer);
        $stmt->bindParam(':bad2_answer', $this->bad2_answer);
        $stmt->bindParam(':bad3_answer', $this->bad3_answer);
        $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
        // execute query
        if($stmt->execute()){
  
            // compare topics tags
            $topic_tags = array();
            $topic_tags_update = array();
            $query_extra = "SELECT topic_id FROM quiz_topics WHERE quiz_id = ".$this->id." ORDER BY topic_id ASC";
            $stmt_extra = $this->conn->prepare($query_extra);
            $stmt_extra->execute();
            if($stmt_extra->rowcount()>0) {
                while($row_extra = $stmt_extra->fetch(PDO::FETCH_ASSOC)){ 
                    $topic_tags[] = $row_extra['topic_id'];
                }
                
            }
            
            for ($i = 0; $i < count($this->topic_tags); $i++) {           
                $topic_tags_update[] = $this->topic_tags[$i];   
            }
            asort($topic_tags_update);

            if (array_diff($topic_tags,$topic_tags_update) != array_diff($topic_tags_update,$topic_tags)) {
                // delete all topics tags and after add again
                $this->conn->query("DELETE FROM quiz_topics WHERE quiz_id = ".$this->id);
                for ($i = 0; $i < count($this->topic_tags); $i++) {           
                    $this->conn->query("INSERT INTO  quiz_topics (quiz_id, topic_id) VALUES (".$this->id.", ".$this->topic_tags[$i].")");  
                }    
            }
         
            
            return true;
        }
        return false;
    }

    // delete
    function delete(){
        
        $query = "UPDATE " . $this->table_name . "
            SET deleted = 1 WHERE id= ?";
    
        // prepare query
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
        // execute query
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    function isAlreadyExist(){
        $query = "SELECT id  FROM ".$this->table_name." 
                    WHERE question = ? AND good_answer = ? AND deleted = 0";

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->question);
        $stmt->bindParam(2, $this->good_answer);
        // execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($stmt->rowCount()>0 && $row['id']!=$this->id) {
            $this->already_exist_id = $row['id'];
            return true;
        } else {
            return false;
        }

        
    }


}

?>