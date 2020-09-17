<?php
include_once '../config/database.php';

class Faculty{
        
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


        function listAll(){
        $query = "SELECT * FROM " . $this->table_name;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();

        return $stmt;
}
}

$database = new Database();
$db = $database->getConnection();
$faculty = new Faculty($db);


if(isset($_GET['p'])) {

        $p = trim($_GET['p']);
        $p = strip_tags($p);
        $p = htmlspecialchars($p);
      

        $stmt = $faculty->listAll();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            switch ($p) {
                case 'list':
                $faculty_arr[$row['id']]=$row['short_name'];
                      break;

             }

            //$faculty_arr[]=array($row['id'] => array(array("name" => $row['name'],"shortname" => $row['shortname'])));



      }

 print_r(json_encode($faculty_arr));

} else echo "Не влезать! Убьёт!ы"

?>