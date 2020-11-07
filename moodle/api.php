<?php
set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] );
require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

DEFINE('MOODLE_URL', 'https://elearning.volgmed.ru');

$moodle_token = '886804c05fb8acf05c7ebaa4740bdf4a';

$MoodleRest = new MoodleRest();
$MoodleRest->setServerAddress(MOODLE_URL."/webservice/rest/server.php");
$MoodleRest->setToken($moodle_token);
$MoodleRest->setReturnFormat(MoodleRest::RETURN_ARRAY);

// Get forum id in course
//$result = $MoodleRest->request('mod_forum_get_forums_by_courses', array('courseids' => array(3297)));

// Make new topic
$result = $MoodleRest->request('mod_forum_add_discussion', array('forumid' => 6302, 'subject' => 'test_subj','message' => 'test'));





// Send + in topic
//$result = $MoodleRest->request('mod_forum_add_discussion_post', array('postid' => 193596, 'subject' => 'test_subj','message' => 'test'));


print_r($result);