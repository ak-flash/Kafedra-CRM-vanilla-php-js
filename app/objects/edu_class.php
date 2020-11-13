<?php

class EduClass {
        
        // database connection and table name
        private $conn;
        private $table_name = "classes";
     
        // object properties
        public $id;
        public $day_of_week;
        public $time_start;
        public $course_id;
        public $group_number;
        public $room_id;
        public $week_type;
        public $error;

        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }

        function class_count() {
            $query = "SELECT COUNT(*) FROM " . $this->table_name . " WHERE user_id = ? and class_semester = ?  AND class_year = ?";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->user_real_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->semester, PDO::PARAM_INT);
            $stmt->bindParam(3, $this->year, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->fetchColumn();
        }

        function list() {

            $data_arr = array();
            $data_arr["classes"] = array();

            $query = "SELECT classes.id,classes.day_of_week,classes.week_type,classes.time_start,classes.time_end,classes.group_number,classes.room_id,classes.room_id,classes.updated_at,users.lastname,users.firstname,users.secondname,faculties.short_name,faculties.course_year,faculties.color,courses.class_duration FROM " . $this->table_name . " LEFT JOIN users ON " . $this->table_name . ".user_id=users.id LEFT JOIN courses ON courses.id=classes.course_id LEFT JOIN faculties ON courses.faculty_id=faculties.id WHERE user_id = ? AND class_semester = ?  AND class_year = ? ORDER BY day_of_week ASC, time_start ASC";
            
            //$query = "SELECT topics.id, course_id, t_number, t_name, topics.updated_at, c_name, topics.semester FROM " . $this->table_name . " LEFT JOIN course ON course.faculty_id=" . (int)$faculty . " WHERE " . $this->table_name . ".course_id = course.id AND " . $this->table_name . ".semester=".(int)$semester." ORDER BY t_number ASC";

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(2, $this->semester, PDO::PARAM_INT);
            $stmt->bindParam(3, $this->year, PDO::PARAM_INT);

            // execute query
            $stmt->execute();
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                //extract($row);
                $user_name = $row['lastname'].' '.mb_substr($row['firstname'], 0, 1).'. '.mb_substr($row['secondname'], 0, 1).'.';

                $data_item=array(
                    "id" => $row['id'],
                    "user_id" => $user_name,
                    "course_id" => $row['course_year'].' - '.$row['short_name'],
                    "color" => $row['color'],
                    "day_of_week" => $row['day_of_week'],
                    "week_type" => $row['week_type'],
                    "time_start" => date("H:i" ,strtotime($row['time_start'])),
                    "time_end" => date("H:i" ,strtotime($row['time_end'])),
                    "group_number" => $row['group_number'],
                    "room_id" => $row['room_id'],
                    "updated_at" => date("H:i d/m/Y" ,strtotime($row['updated_at'])),
                );
                array_push($data_arr["classes"], $data_item);
                
            }
            
            return json_encode($data_arr);
        }




        // get single data
        function show($id) {

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
            $data_arr = array(
                "id" => $row['id'],
                "user_id" => $row['user_id'],
                "course_id" => $row['course_id'],
                "day_of_week" => $row['day_of_week'],
                "week_type" => $row['week_type'],
                "time_start" => date("H:i" ,strtotime($row['time_start'])),
                "time_end" => date("H:i" ,strtotime($row['time_end'])),
                "group_number" => $row['group_number'],
                "room_id" => $row['room_id'],
                "moodle_forum_topic" => $row['moodle_forum_topic'],
                "moodle_forum_end_messages" => $row['moodle_forum_end_messages'],
                "tabel" => $row['tabel'],
            );
                
            return json_encode($data_arr);
            
        }


            // create 
        function create(): bool
        {
            $minutes_offset = '-'.rand(1, MOODLE_MESSAGES_TIME_OFFSET).' minutes';

            $randomTime_start = date("H:i", strtotime($minutes_offset, strtotime($this->time_start))).':00';
            $randomTime_end = date("H:i", strtotime($minutes_offset, strtotime($this->time_end))).':00';

            // query to insert record
            $query = "INSERT INTO  ". $this->table_name ." 
                            (user_id, course_id, class_semester, class_year, time_start, time_start_temp, time_end, time_end_temp, day_of_week, week_type, group_number, room_id, moodle_forum_topic, moodle_forum_end_messages, tabel)
                    VALUES
                            (:user_id, :course_id, :semester, :year, :time_start, :time_start_temp, :time_end, :time_end_temp, :day_of_week, :week_type, :group_number, :room_id, :moodle_forum_topic, :moodle_forum_end_messages, :tabel)";
        
            // prepare query
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindParam(':course_id', $this->course_id, PDO::PARAM_INT);
            $stmt->bindParam(':semester', $this->semester, PDO::PARAM_INT);
            $stmt->bindParam(':year', $this->year, PDO::PARAM_INT);
            $stmt->bindParam(':time_start', $this->time_start);
            $stmt->bindParam(':time_end', $this->time_end);
            $stmt->bindParam(':time_start_temp', $randomTime_start);
            $stmt->bindParam(':time_end_temp', $randomTime_end);
            $stmt->bindParam(':day_of_week', $this->day_of_week);
            $stmt->bindParam(':week_type', $this->week_type);
            $stmt->bindParam(':group_number', $this->group_number, PDO::PARAM_INT);
            $stmt->bindParam(':room_id', $this->room_id, PDO::PARAM_INT);
            $stmt->bindParam(':moodle_forum_topic', $this->moodle_forum_topic, PDO::PARAM_INT);
            $stmt->bindParam(':moodle_forum_end_messages', $this->moodle_forum_end_messages, PDO::PARAM_INT);
            $stmt->bindParam(':tabel', $this->tabel, PDO::PARAM_INT);

            // execute query
            if($stmt->execute()){
                $this->id = $this->conn->lastInsertId();
                return true;
            } else $this->error = $stmt->errorInfo();

            return false;
        }

        // update 
        function update(): bool
        {
            // query to insert record
            $query = "UPDATE
                        " . $this->table_name . "
                SET
                    user_id = :user_id, course_id = :course_id, day_of_week = :day_of_week, time_start = :time_start, time_end = :time_end, group_number = :group_number, room_id = :room_id, week_type = :week_type, moodle_forum_topic = :moodle_forum_topic, moodle_forum_end_messages = :moodle_forum_end_messages, tabel = :tabel
                WHERE
                    id = :id";
        
            // prepare query
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':course_id', $this->course_id, PDO::PARAM_INT);
            $stmt->bindParam(':day_of_week', $this->day_of_week);
            $stmt->bindParam(':time_start', $this->time_start);
            $stmt->bindParam(':time_end', $this->time_end);
            $stmt->bindParam(':group_number', $this->group_number, PDO::PARAM_INT);
            $stmt->bindParam(':room_id', $this->room_id, PDO::PARAM_INT);
            $stmt->bindParam(':week_type', $this->week_type, PDO::PARAM_INT);
            $stmt->bindParam(':moodle_forum_topic', $this->moodle_forum_topic, PDO::PARAM_INT);
            $stmt->bindParam(':moodle_forum_end_messages', $this->moodle_forum_end_messages, PDO::PARAM_INT);
            $stmt->bindParam(':tabel', $this->tabel, PDO::PARAM_INT);

            // execute query
            if($stmt->execute()){
                return true;
            } else $this->error = $stmt->errorInfo();

            return false;
        }


        // delete
        function delete(): bool
        {
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
            } else $this->error = $stmt->errorInfo();

            return false;
        }
}