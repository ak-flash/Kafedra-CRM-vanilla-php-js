<?php

class Faculty {
        
        // database connection and table name
        private $conn;
        private $table_name = "faculties";
     
        // object properties
        public $Id;
        public $Name;
        public $ShortName;
        public $CourseYear;

        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }

        function list() {
            $query = "SELECT * FROM " . $this->table_name;
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $faculty_arr[$row['id']]=$row['short_name'];
                }

            return json_encode($faculty_arr);
        }  
        
        function courses($faculty = 0, $semester = 0) {
            $query = "SELECT id, c_name, c_short_name FROM courses WHERE faculty_id = ? AND (semester = ? OR semester = 12)";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(1, $faculty);
            $stmt->bindParam(2, $semester);
            
            // execute query
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $data_arr[$row['id']]=$row['c_name'];
                }

            return json_encode($data_arr);
        }   
}