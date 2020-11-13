<?php
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] );

if(empty($_SERVER['DOCUMENT_ROOT'])) $_SERVER['DOCUMENT_ROOT'] = '/www/wwwroot/kafedra.ak-vps.tk';

include_once $_SERVER['DOCUMENT_ROOT'].'/app/config/core.php';

#echo $_SERVER['DOCUMENT_ROOT'].'app/config/core.php';

$working_days = array(1, 2, 3, 4, 5, 6);
$working_time_start = strtotime('07:58:00');
$working_time_end = strtotime('17:58:00');
$current_year = date('Y');
$current_semester = getCurrentSemester();
$time_trigger = date('H:i').':00';
$day_of_week_trigger = getDayOfWeek();
if (date('W')%2==1) $week_type_trigger = 2; else $week_type_trigger = 1;

$test_moodle_course_id = 2637;

function getDayOfWeek (): string
{
    $day = "";

    switch (date('N')) {
        case 1:
            $day = "пн";
            break;
        case 2:
            $day = "вт";
            break;
        case 3:
            $day = "ср";
            break;
        case 4:
            $day = "чт";
            break;
        case 5:
            $day = "пт";
            break;
        case 6:
            $day = "сб";
            break;
        case 7:
            $day = "вс";
            break;
    }

    return $day;
}

function getMoodleToken (): array
{
    global $database;
    $moodle_token = array();

    $query = "SELECT id, moodle_token FROM users";

    $stmt = $database->conn->prepare($query);
    $stmt->execute();

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $moodle_token[$row['id']] = $row['moodle_token'];
    }

    return $moodle_token;
}

function testMoodleConnection ($moodle_course_id) {
    global $MoodleRest, $moodle_token;

    $result = $MoodleRest->request('mod_forum_get_forums_by_courses', array('courseids' => array($moodle_course_id)));

    if(!empty($result)) {
        echo '  Connection successful to '.MOODLE_URL;
    } else {
        $error_message = '  Failed to connect to '.MOODLE_URL;;
        echo $error_message;
        write_log(0, '  Failed to connect to '.MOODLE_URL);
    }
}

function isAlreadySent ($moodle_course_id, $group_number): bool
{
    global $MoodleRest;

    $result = $MoodleRest->request('mod_forum_get_forums_by_courses', array('courseids' => array($moodle_course_id)));

    if(!empty($result)) {

        $forum_id = 0;

        for ($x=0; $x<count($result); $x++) {
            if(strpos($result[$x]['name'], $group_number)) $forum_id = $result[$x]['id'];
        }

        $result = $MoodleRest->request('mod_forum_get_forum_discussions_paginated', array('forumid' => $forum_id));

        if (empty($result["warnings"]) && empty($result["errorcode"])) {

            for ($x = 0; $x < count($result["discussions"]); $x++) {
                $pos = strpos($result["discussions"][$x]['name'], date('d.m.Y'));

                if ($pos !== false) {
                    return true;
                }
            }

        }
    }

    return false;
}

function randTime ($class_id, $edu_class_time, $mode_type) {
    global $database;

    $minutes_offset = '-'.rand(1, MOODLE_MESSAGES_TIME_OFFSET).' minutes';

    $randomTime = date("H:i", strtotime($minutes_offset, strtotime($edu_class_time))).':00';

    $query = "UPDATE classes SET ".$mode_type." = :mode_type WHERE id = :id";

    // prepare query
    $stmt = $database->conn->prepare($query);
    $stmt->bindParam(':id', $class_id, PDO::PARAM_INT);
    $stmt->bindParam(':mode_type', $randomTime);
    $stmt->execute();

}


function sendToMoodleForum ($user_id, $type, $moodle_course_id, $group_number) {
    global $MoodleRest;

    $moodle_token = getMoodleToken();

    if(!empty($moodle_token[$user_id])) {
        $MoodleRest->setToken($moodle_token[$user_id]);

        $result = $MoodleRest->request('mod_forum_get_forums_by_courses', array('courseids' => array($moodle_course_id)));

        if(!empty($result)) {
            $forum_id =0;
            for ($x=0; $x<count($result); $x++) {
                if(strpos($result[$x]['name'], $group_number)) $forum_id = $result[$x]['id'];
            }

            $topic_name = date('d.m.Y');

            if($type == 'make_new_topic') {
                // Hello. Please indicate your presence in the class by sending the " + "sign in the reply message (the "reply" button)."
                $result = $MoodleRest->request('mod_forum_add_discussion', array('forumid' => $forum_id, 'subject' => $topic_name,'message' => '
Здравствуйте. Пожалуйста, отметьтесь о своем присутствии на занятии, отправив в ответном сообщении (кнопка "ответить") знак "+"'));

                if (empty($result["warnings"] && empty($result["errorcode"]))) {
                    $answer_message = 'Тема успешно создана на портале! Id форума - '.$moodle_course_id.' Группа - '.$group_number;
                    echo makeJsonMessage($answer_message);
                } else {
                    $error_message = 'Ошибка создания темы на портале! Курс - '.$moodle_course_id.' Группа - '.$group_number;
                    echo makeJsonMessage($error_message);
                    write_log($user_id, $error_message);
                }

            }

            if($type == 'send_end_message') {
                $result = $MoodleRest->request('mod_forum_get_forum_discussions_paginated', array('forumid' => $forum_id));

                if (empty($result["warnings"]) && empty($result["errorcode"])) {
                    $post_id = 0;
                    for ($x=0; $x<count($result["discussions"]); $x++) {
                        $pos = strpos($result["discussions"][$x]['name'], $topic_name);
                        if($pos !== false) $post_id = $result["discussions"][$x]['id'];
                    }

                    if (empty($result["warnings"]) && empty($result["errorcode"]) && $post_id!=0) {
                        $result = $MoodleRest->request('mod_forum_add_discussion_post', array('postid' => $post_id, 'subject' => 'Сообщение об окончании занятия','message' => '+'));
                        if (empty($result["warnings"] && empty($result["errorcode"]))) {
                            $answer_message = 'Сообщение об окончании занятия успешно отправлено на портал! Id форума - '.$moodle_course_id.' Группа - '.$group_number;
                            echo makeJsonMessage($answer_message);
                        } else {
                            $error_message = 'Ошибка отправки сообщения об окончании занятия на портал! '.$result["message"].' Курс - '.$moodle_course_id.' Группа - '.$group_number;
                            echo makeJsonMessage($error_message);
                            write_log($user_id, $error_message);
                        }
                    } else {
                        $error_message = 'Ошибка! Тема занятия НЕ создана на портале!  Курс - '.$moodle_course_id.' Группа - '.$group_number;
                        echo makeJsonMessage($error_message);
                        write_log($user_id, $error_message);
                    }

                }
            }

        }
    } else {
        write_log($user_id, 'Ошибка! Moodle token у пользователя не задан');
    }

}

if(in_array(date('N'), $working_days) && ($working_time_start < strtotime($time_trigger)) && (strtotime($time_trigger) < $working_time_end)) {

    $database = new Database();
    $database->getConnection();

    $MoodleRest = new MoodleRest();
    $MoodleRest->setServerAddress(MOODLE_URL."/webservice/rest/server.php");
    $MoodleRest->setReturnFormat(MoodleRest::RETURN_ARRAY);

    $moodle_token = getMoodleToken ();
    $MoodleRest->setToken($moodle_token['1']);

    #isAlreadySended(2637, '336');
    #if(isAlreadySended(2637, '336')) echo 'already'; else echo 'not';

    $query = "SELECT classes.id, classes.day_of_week, classes.time_start, classes.time_start_temp, classes.time_end, classes.time_end_temp, classes.group_number, classes.moodle_forum_topic, classes.moodle_forum_end_messages, classes.week_type, classes.user_id, classes.updated_at, courses.date_start, courses.date_end, courses.moodle_course_id, faculties.course_year FROM classes LEFT JOIN courses ON classes.course_id = courses.id LEFT JOIN faculties ON courses.faculty_id = faculties.id WHERE class_year = :class_year AND class_semester = :class_semester AND day_of_week = :day_of_week AND (week_type = :week_type OR week_type = 0) AND (time_start_temp = :time_start_temp OR time_end_temp = :time_end_temp) AND (moodle_forum_topic = 1 OR moodle_forum_end_messages = 1)";
    //
    $stmt = $database->conn->prepare($query);
    $stmt->bindParam(':class_year', $current_year, PDO::PARAM_INT);
    $stmt->bindParam(':class_semester', $current_semester, PDO::PARAM_INT);
    $stmt->bindParam(':time_start_temp', $time_trigger);
    $stmt->bindParam(':time_end_temp', $time_trigger);
    $stmt->bindParam(':day_of_week', $day_of_week_trigger);
    $stmt->bindParam(':week_type', $week_type_trigger);
    $stmt->execute();

    if ($stmt->rowCount()>0) {

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

            $full_group_number = $row['course_year'].$row['group_number'];

            if($row['moodle_forum_topic']==1 && $row['time_start_temp']==$time_trigger) {
                if(!isAlreadySent($row['moodle_course_id'], $full_group_number)) {
                    sendToMoodleForum ($row['user_id'], 'make_new_topic', $row['moodle_course_id'], $full_group_number);
                    // Save random time to base
                    randTime ($row['id'], $row['time_start'], 'time_start_temp');
                } else {
                    echo makeJsonMessage("The topic already exists");
                }

            }



            if($row['moodle_forum_end_messages']==1 && $row['time_end_temp']==$time_trigger) {

                $origin = new DateTime($row['updated_at']);
                $target = new DateTime('now');
                $interval = $origin->diff($target);
                $interval_in_minutes = $interval->h * 60 + $interval->i;

                if($interval_in_minutes > MOODLE_MESSAGES_TIME_OFFSET) {
                    sendToMoodleForum ($row['user_id'], 'send_end_message', $row['moodle_course_id'], $full_group_number);
                    // Save random time to base
                    randTime ($row['id'], $row['time_end'], 'time_end_temp');
                } else echo makeJsonMessage("Message already sent");;

            }
        }

    } else {
        echo makeJsonMessage("OK - but trigger time not now");
        #testMoodleConnection ($test_moodle_course_id);
    }
} else {
    echo makeJsonMessage("Not working time");
}