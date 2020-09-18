<?php

class Topic {
        
        // database connection and table name
        private $conn;
        private $table_name = "topics";
     
        // object properties
        public $id;
        public $t_number;
        public $t_name;
        public $semester;
        public $course_id;

        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
       
        function list($faculty,$semester){
            //if($semester==1) $semester=[1,3,5,7,9,11,34,56,78]; // Autumn semester
            //if($semester==2) $semester=[2,4,6,8,10,12,34,56,78]; // Spring semester
            $topics_arr = array();
            $topics_arr["topics"] = array();

            $query = "SELECT topics.id, course_id, t_number, t_name, c_name, topics.semester FROM " . $this->table_name . " LEFT JOIN course ON course.faculty_id=" . $faculty . " WHERE " . $this->table_name . ".course_id = course.id AND " . $this->table_name . ".semester=".(int)$semester." ORDER BY t_number ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                //extract($row);
                $topic_item=array(
                    "id" => $row['id'],
                    "t_name" => $row['t_name'],
                    "t_number" => $row['t_number'],
                );
                array_push($topics_arr["topics"], $topic_item);
        
                $topics_arr['c_name'] = $row['c_name'];
                $topics_arr['course_id'] = $row['course_id'];
                
            }
            return json_encode($topics_arr);
        }

        function listshort($faculty,$semester){
            $query = "SELECT topics.id, t_number, t_name FROM " . $this->table_name . "  LEFT JOIN course ON course.faculty_id=" . $faculty . " WHERE " . $this->table_name . ".course_id = course.id AND " . $this->table_name . ".semester=".(int)$semester." ORDER BY t_number ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
    
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                $topics_arr[$row['id']]=$row['t_number'].' - '.$row['t_name'];    
            }

            return json_encode($topics_arr);
            }

        // get single data
        function show($id){
        
            // select all query
            $query = "SELECT * FROM " . $this->table_name . " WHERE id= '".$id."'";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $topic_arr["topics"]=array(
                "id" => $row['id'],
                "t_name" => $row['t_name'],
                "t_number" => $row['t_number'],
            );
            
            return json_encode($topic_arr);
        }


            // create 
        function create(){     
            if($this->isAlreadyExist()){
                return false;
            }        
            // query to insert record
            $query = "INSERT INTO  ". $this->table_name ." 
                            (`t_name`, `t_number`, `semester`,`course_id`)
                    VALUES
                            ('".$this->t_name."', '".$this->t_number."', '".$this->semester."', '".$this->course_id."')";
        
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
            if($this->isAlreadyExist()){
                return false;
            }  
            // query to insert record
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                    t_name='".$this->t_name."', t_number='".$this->t_number."'
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
                    t_number='".$this->t_number."' AND semester='".$this->semester."' AND course_id='".$this->course_id."'";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // execute query
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0 && $row['id'] != $this->id){
                return true;
            }
            else{
                return false;
            }
        }
}