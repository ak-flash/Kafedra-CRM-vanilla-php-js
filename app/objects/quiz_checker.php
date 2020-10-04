<?php

class Quiz_checker {
        
        // database connection and table name
        private $conn;
        private $table_name = "quiz_key";
     
        // object properties
        public $id;
        public $topic_id;
        public $variant;
        public $version;
        public $question_part;
        public $user_id;

        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
       

        function load_versions($topic_id = 0) {
                    // select all query
                $query = "SELECT version FROM " . $this->table_name . "
                        WHERE topic_id = ? GROUP BY version";

                // prepare query statement
                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(1, $topic_id, PDO::PARAM_INT);
                // execute query
                $stmt->execute();

                $data_arr = array();
                $data_arr["key_versions"] = array();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                    
                    $data_item=array(
                        "version" => $row['version'],
                    );
                    array_push($data_arr["key_versions"], $data_item);
                }

                return json_encode($data_arr);
          }
        
        function load_variants($topic_id = 0, $version = 1) {
                // select all query
            $query = "SELECT variant FROM " . $this->table_name . "
                    WHERE topic_id = ? AND version = ?";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $topic_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $version, PDO::PARAM_INT);
            // execute query
            $stmt->execute();

            $data_arr = array();
            $data_arr["key_variants"] = array();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                
                $data_item=array(
                    "variant" => $row['variant'],
                );
                array_push($data_arr["key_variants"], $data_item);
            }

            return json_encode($data_arr);
        }
          
        function load($topic_id = 0, $version = 1, $variant = 0) {
               // select all query
                $query = "SELECT * FROM " . $this->table_name . "
                            WHERE topic_id = :topic_id AND version = :version AND variant = :variant";

                // prepare query statement
                $stmt = $this->conn->prepare($query);

                $stmt->bindParam(':topic_id', $topic_id, PDO::PARAM_INT);
                $stmt->bindParam(':version', $version, PDO::PARAM_INT);
                $stmt->bindParam(':variant', $variant, PDO::PARAM_INT);
                
                // execute query
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC); 

                if($row['q1']!=0 && $row['q2']!=0 && $row['q3']!=0 && $row['q4']!=0 && $row['q5']!=0 && $row['q6']!=0 && $row['q7']!=0 && $row['q8']!=0 && $row['q9']!=0 && $row['q10']!=0) {
                   return true;
                }
                
                return false;
        }
}        
?>