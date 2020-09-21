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
       
        function list($semester = 0, $course_id = 0){
            //if($semester==1) $semester=[1,3,5,7,9,11,34,56,78]; // Autumn semester
            //if($semester==2) $semester=[2,4,6,8,10,12,34,56,78]; // Spring semester
            $data_arr = array();
            $data_arr["topics"] = array();

            $query = "SELECT * FROM " . $this->table_name . " WHERE course_id = ? AND semester = ? ORDER BY t_number ASC";
            
            //$query = "SELECT topics.id, course_id, t_number, t_name, topics.updated_at, c_name, topics.semester FROM " . $this->table_name . " LEFT JOIN course ON course.faculty_id=" . (int)$faculty . " WHERE " . $this->table_name . ".course_id = course.id AND " . $this->table_name . ".semester=".(int)$semester." ORDER BY t_number ASC";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->course_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->semester, PDO::PARAM_INT);

            // execute query
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                //extract($row);
                $data_item=array(
                    "id" => $row['id'],
                    "t_name" => $row['t_name'],
                    "t_number" => $row['t_number'],
                    "updated_at" => date("H:i d/m/Y" ,strtotime($row['updated_at'])),
                );
                array_push($data_arr["topics"], $data_item);
                
            }
            
            return json_encode($data_arr);
        }

        function listshort($semester = 0, $course_id = 0){
            $query = "SELECT * FROM " . $this->table_name . " WHERE course_id = ? AND semester = ? ORDER BY t_number ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->course_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->semester, PDO::PARAM_INT);
            // execute query
            $stmt->execute();
    
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                $data_arr[$row['id']]=$row['t_number'].' - '.$row['t_name'];    
            }

            return json_encode($data_arr);
            }

        // get single data
        function show($id){
        
            // select all query
            $query = "SELECT * FROM " . $this->table_name . "
                    WHERE id = ?
                    LIMIT 0,1";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
            // execute query
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $data_arr=array(
                "id" => $row['id'],
                "t_name" => $row['t_name'],
                "t_number" => $row['t_number'],
            );
            
            return json_encode($data_arr);
        }


            // create 
        function create(){     
            if($this->isAlreadyExist()){
                return false;
            }        
            // query to insert record
            $query = "INSERT INTO  ". $this->table_name ." 
                            (t_name, t_number, semester, course_id)
                    VALUES
                            (:t_name, :t_number, :semester, :course_id)";
        
            // prepare query
            $stmt = $this->conn->prepare($query);
        
            $stmt->bindParam(':course_id', $this->course_id, PDO::PARAM_INT);
            $stmt->bindParam(':semester', $this->semester, PDO::PARAM_INT);
            $stmt->bindParam(':t_name', $this->t_name);
            $stmt->bindParam(':t_number', $this->t_number, PDO::PARAM_INT);
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
                    t_name = :t_name, t_number = :t_number
                WHERE
                    id = :id";
        
            // prepare query
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':t_name', $this->t_name);
            $stmt->bindParam(':t_number', $this->t_number, PDO::PARAM_INT);
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
               t_number = :t_number AND semester = :semester AND course_id = :course_id";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':semester', $this->semester, PDO::PARAM_INT);
            $stmt->bindParam(':course_id', $this->course_id, PDO::PARAM_INT);
            $stmt->bindParam(':t_number', $this->t_number, PDO::PARAM_INT);

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

        // delete
        function delete(){         
            // query to insert record
            $query = "DELETE FROM
                        " . $this->table_name . "
                    WHERE
                        id = ?";
        
            // prepare query
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id, PDO::PARAM_INT);
            // execute query
            if($stmt->execute()){
                return true;
            }
            return false;
        }
}