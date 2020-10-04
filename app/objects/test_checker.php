<?php

class Test_checker {
        
        // database connection and table name
        private $conn;
        private $table_name = "quiz_key";
     
        // object properties
        public $id;
        public $faculty;
        public $semestr;
        public $topic;
        public $variant;
        public $version;

        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
       

        function get_quiz_key($faculty,$semestr, $topic) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE `faculty`=".$faculty." AND `topic`=".$topic." AND `semestr`=".$semestr;
        // prepare query statement
        $stmt = $this->conn->prepare($query);
        // execute query
        $stmt->execute();

        return $stmt;
        }

        function get_quiz_key_id($faculty,$semestr, $topic,$variant,$version) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE `faculty`=".$faculty." AND `topic`=".$topic." AND `semestr`=".$semestr." AND `version`=".$version." AND `variant`=".$variant;
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
    
            return $stmt;
            }

        function get_quiz_variant_id($id) {
                $query = "SELECT * FROM " . $this->table_name . " WHERE `id`=".$id;
                // prepare query statement
                $stmt = $this->conn->prepare($query);
                // execute query
                $stmt->execute();
        
                return $stmt;
                }
        
          function get_quiz_question_id($id) {
                    $query = "SELECT `id`,`question`,`good`,`bad1`,`bad2`,`bad3` FROM `quiz` WHERE `id`=".$id;
                    // prepare query statement
                    $stmt = $this->conn->prepare($query);
                    // execute query
                    $stmt->execute();
            
                    return $stmt;
          }

}        

$database = new Database();
$db = $database->getConnection();
$test = new Test_checker($db);  


if(isset($_GET['action'])&&@$_GET['action']=='list'&&isset($_GET['f'])&&isset($_GET['s'])&&isset($_GET['t'])) {

    $p = trim($_GET['action']);
    $p = strip_tags($p);
    $p = htmlspecialchars($p);
    
    $keys_arr=array();
    $keys_arr["keys"]=array();
    $r_question_part = (int)$_GET['question_part'];
    
    $stmt = $test->get_quiz_key((int)$_GET['f'],(int)$_GET['s'],(int)$_GET['t']);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
       
        
if($r_question_part==1){
    $q1a=$row['q1-a'];$q2a=$row['q2-a'];$q3a=$row['q3-a'];$q4a=$row['q4-a'];$q5a=$row['q5-a'];
    $q6a=$row['q6-a'];$q7a=$row['q7-a'];$q8a=$row['q8-a'];$q9a=$row['q9-a'];$q10a=$row['q10-a'];
} else {
    $q1a=$row['q11-a'];$q2a=$row['q12-a'];$q3a=$row['q13-a'];$q4a=$row['q14-a'];$q5a=$row['q15-a'];
    $q6a=$row['q16-a'];$q7a=$row['q17-a'];$q8a=$row['q18-a'];$q9a=$row['q19-a'];$q10a=$row['q20-a'];
} 

        $keys_item=array(
            "id" => $row['id'],
            "timestamp" => $row['timestamp'],
            "owner" => $row['owner'],
            "version" => $row['version'],
            "variant" => $row['variant'],
            "q1a" => $q1a,"q2a" => $q2a,
            "q3a" => $q3a,"q4a" =>$q4a,
            "q5a" => $q5a,"q6a" => $q6a,
            "q7a" => $q7a,"q8a" => $q8a,
            "q9a" => $q9a,"q10a" => $q10a
            
        );
        array_push($keys_arr["keys"], $keys_item);
    }
    echo json_encode($keys_arr["keys"]);
}


if(isset($_GET['action'])&&@$_GET['action']=='check'&&isset($_POST['faculty'])&&isset($_POST['semestr'])&&isset($_POST['topic'])&&isset($_POST['variant'])) {  
        

        $r_faculty = (int)$_POST['faculty'];
        $r_semestr = (int)$_POST['semestr'];
        $r_question_part = (int)$_POST['question_part'];
        $r_topic = (int)$_POST['topic'];
        $r_variant = (int)$_POST['variant'];
        $r_version = (int)$_POST['version'];
        
        $stmt = $test->get_quiz_key_id($r_faculty,$r_semestr,$r_topic,$r_variant,$r_version);
        
        if($stmt->rowCount() == 1){
            // get retrieved row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
       // Проверка соответствия ответов
       $res=0;
       $wrong_answ=0;

       if($r_question_part==1){
        $q1a=$row['q1-a'];$q2a=$row['q2-a'];$q3a=$row['q3-a'];$q4a=$row['q4-a'];$q5a=$row['q5-a'];
        $q6a=$row['q6-a'];$q7a=$row['q7-a'];$q8a=$row['q8-a'];$q9a=$row['q9-a'];$q10a=$row['q10-a'];
        if($q1a==0||$q2a==0||$q3a==0||$q4a==0||$q5a==0||$q6a==0||$q7a==0||$q8a==0||$q9a==0||$q10a==0) $res=0; else $res=1;
    } else {
        $q1a=$row['q11-a'];$q2a=$row['q12-a'];$q3a=$row['q13-a'];$q4a=$row['q14-a'];$q5a=$row['q15-a'];
        $q6a=$row['q16-a'];$q7a=$row['q17-a'];$q8a=$row['q18-a'];$q9a=$row['q19-a'];$q10a=$row['q20-a'];
        if($q1a==0||$q2a==0||$q3a==0||$q4a==0||$q5a==0||$q6a==0||$q7a==0||$q8a==0||$q9a==0||$q10a==0) $res=0; else $res=1;
    }      

       if($q1a==(int)$_POST['q1']) $q1=1; else {$q1=0;$wrong_answ++;}
       if($q2a==(int)$_POST['q2']) $q2=1; else {$q2=0;$wrong_answ++;}
       if($q3a==(int)$_POST['q3']) $q3=1; else {$q3=0;$wrong_answ++;}
       if($q4a==(int)$_POST['q4']) $q4=1; else {$q4=0;$wrong_answ++;}
       if($q5a==(int)$_POST['q5']) $q5=1; else {$q5=0;$wrong_answ++;}
       if($q6a==(int)$_POST['q6']) $q6=1; else {$q6=0;$wrong_answ++;}
       if($q7a==(int)$_POST['q7']) $q7=1; else {$q7=0;$wrong_answ++;}
       if($q8a==(int)$_POST['q8']) $q8=1; else {$q8=0;$wrong_answ++;}
       if($q9a==(int)$_POST['q9']) $q9=1; else {$q9=0;$wrong_answ++;}
       if($q10a==(int)$_POST['q10']) $q10=1; else {$q10=0;$wrong_answ++;}

            $result=($q1+$q2+$q3+$q4+$q5+$q6+$q7+$q8+$q9+$q10)*10;

       //$check_arr[$row['id']]=$row['q1-a'].' - '.$row['q2-a'];
        if((int)$_POST['demo']==1) $check_arr=array(
            "status" => false,
            "message" => 'Загружено',
            "empty" => $res      
            ); 
            else 
        $check_arr=array(
            "status" => true,
            "result" => $result,
            "result_info" => (10-$wrong_answ)      
            );

            
        
                                } 
                                 else {
                                   
                                    if($stmt->rowCount() == 0) {
                                        $check_arr=array(
                                        "status" => false,
                                        "message" => "В базе не обнаружено ответов для данного теста"          
                                        );
                                    }
                                    if($stmt->rowCount() > 1) {
                                        $check_arr=array(
                                        "status" => false,
                                        "message" => "В базе обнаружено несколько записей ответов для данного теста"         
                                        );
                                    }

                                 }                       

print_r(json_encode($check_arr)); 

}

if(isset($_GET['id'])&&@$_GET['id']!=0) {

$r_id = (int)$_GET['id'];

$stmt = $test->get_quiz_variant_id($r_id);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

//  array
$questions_arr = array(
    "version" => $row['version'],
    "variant" => $row['variant'],
    'list' => array()
);


for ($i = 1; $i <= 20; $i++) {
            if ($row['q'.$i]==0||$row['q'.$i.'-a']==0) {
                break;
            }
     $quest=$test->get_quiz_question_id($row['q'.$i])->fetch(PDO::FETCH_ASSOC);
     $question_item=array(
        "number" => $i,
        "question_id" => $quest['id'],
        "question" => $quest['question'],
        "good_position" => $row['q'.$i.'-a'],
        "good" => $quest['good'],
        "bad1" => $quest['bad1'],
        "bad2" => $quest['bad2'],
        "bad3" => $quest['bad3'],        
      );
    
    array_push($questions_arr["list"], $question_item);
}



echo json_encode($questions_arr); 
}

?>