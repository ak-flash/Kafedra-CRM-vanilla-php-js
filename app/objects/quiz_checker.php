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
        public $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10;
        public $q11, $q12, $q13, $q14, $q15, $q16, $q17, $q18, $q19, $q20;

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

        function check($topic_id = 0, $version = 1, $variant = 0, $question_part = 1) {

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
            
            if($question_part==1) {
                $q1a = $row['q1a'];
                $q2a = $row['q2a'];
                $q3a = $row['q3a'];
                $q4a = $row['q4a'];
                $q5a = $row['q5a'];
                $q6a = $row['q6a'];
                $q7a = $row['q7a'];
                $q8a = $row['q8a'];
                $q9a = $row['q9a'];
                $q10a = $row['q10a'];
            }

            if($q1a==$this->q1) $q1=1; else $q1=0;
            if($q2a==$this->q2) $q2=1; else $q2=0;
            if($q3a==$this->q3) $q3=1; else $q3=0;
            if($q4a==$this->q4) $q4=1; else $q4=0;
            if($q5a==$this->q5) $q5=1; else $q5=0;
            if($q6a==$this->q6) $q6=1; else $q6=0;
            if($q7a==$this->q7) $q7=1; else $q7=0;
            if($q8a==$this->q8) $q8=1; else $q8=0;
            if($q9a==$this->q9) $q9=1; else $q9=0;
            if($q10a==$this->q10) $q10=1; else $q10=0;

            $result = ($q1 + $q2 + $q3 + $q4 + $q5 + $q6 + $q7 + $q8 + $q9 + $q10);

            $data_arr=array(
                "result" => $result,
            );
            
            return json_encode($data_arr);
        }

}        
?>