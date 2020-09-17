<?php
  session_start();
  
  // include database and object files
    include_once '../../config/database.php';
    include_once 'quiz.php';
     
    // get database connection
    $database = new Database();
    $db = $database->getConnection();
     
    // prepare object
    $quiz = new Quiz($db);
     
    // query 
    
    
    if(isset($_GET['id'])&&@$_GET['id']!=''&&@$_GET['id']!=0){
        
        $stmt = $quiz->read_single((int)$_GET['id']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $quiz_arr["quiz"]=array(
            "id" => $row['id'],
            "question" => $row['question'],
            "good" => $row['good'],
            "bad1" => $row['bad1'],
            "bad2" => $row['bad2'],
            "bad3" => $row['bad3'],
            "tag_topic1" => $row['tag_topic1'],
            "tag_topic2" => $row['tag_topic2'],
            "tag_lech" => $row['tag_lech'],"tag_ped" => $row['tag_ped'],"tag_mbf3" => $row['tag_mbf3'],"tag_mbf4" => $row['tag_mbf4'],
            "tag_stom" => $row['tag_stom'],"tag_mpd" => $row['tag_mpd'],"tag_lecheng" => $row['tag_lecheng'],"tag_stomeng" => $row['tag_stomeng']
        );
        
        echo json_encode($quiz_arr["quiz"]);

    }
        else {
    if(isset($_GET['limit'])&&isset($_GET['page'])&&isset($_GET['filter'])&&isset($_GET['filter_value'])){
    $stmt = $quiz->read((int)$_GET['limit'], (int)$_GET['page'], (int)$_GET['filter'], (int)$_GET['filter_value']);
    $num = $stmt->rowCount();
    $questions_count = $quiz->questions_count((int)$_GET['filter'], (int)$_GET['filter_value']);
    $q_count = $questions_count->fetch(PDO::FETCH_NUM);
    // check if more than 0 record found
    if($num>0){
     
        //  array
        $quiz_arr=array();
        $quiz_arr["quiz"]=array();
       
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $quiz_item=array(
                "id" => $id,
                "question" => $question,
                "good" => $good,
                "bad1" => $bad1,
                "bad2" => $bad2,
                "bad3" => $bad3,
                "tag_topic1" => $tag_topic1,
                "tag_topic2" => $tag_topic2,
                "tag_lech" => $tag_lech,"tag_ped" => $tag_ped,"tag_mbf3" => $tag_mbf3,"tag_mbf4" => $tag_mbf4,
                "tag_stom" => $tag_stom,"tag_mpd" => $tag_mpd,"tag_lecheng" => $tag_lecheng,"tag_stomeng" => $tag_stomeng
            );
            array_push($quiz_arr["quiz"], $quiz_item);
        }
        array_push($quiz_arr["quiz"], array('quiz_count' => $q_count[0]));

        $_SESSION['user_quiz_page'] = (int)$_GET['page'];
        echo json_encode($quiz_arr["quiz"]);
    }
} 
    else {
        echo json_encode(array());
    }

}
?>