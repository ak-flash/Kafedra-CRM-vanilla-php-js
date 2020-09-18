<?php

class Faculty {
        
        // database connection and table name
        private $conn;
        private $table_name = "faculty";
     
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
}