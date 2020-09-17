<?php
session_start();
include_once '../config/database.php';

class User {
     
        // database connection and table name
        private $conn;
        private $table_name = "users";
     
        // object properties
        public $id;
        public $username;
        public $password;
        public $name;
        public $surname;
        public $shortname;
        public $group;
        public $email;

        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        // signup user
        function signup(){
        
        }

        function list(){
            $users_list["users"]=array();
            
            $query = "SELECT `username`, `name`, `surname`, `shortname` FROM " . $this->table_name . " ORDER BY `group` ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                $user_item = array(
                    "username" => $row['username'],
                    "name" => $row['name'],
                    "surname" => $row['surname'],
                );
                array_push($users_list["users"], $user_item);
            }
        
        return json_encode($users_list);;
    }
        
        // login user
        function login(){
            // receive password hash
            $query = "SELECT
                `password`
            FROM
                " . $this->table_name . " 
            WHERE
                username='".$this->username."' LIMIT 1";
        
                // prepare query statement
            $temp = $this->conn->prepare($query);
            // execute query
            $temp->execute();
            $row = $temp->fetch(PDO::FETCH_ASSOC);
            
            if(password_verify($this->password,$row['password'])){

                $query = "SELECT
                    `id`, `username`, `password`, `name`, `surname`, `shortname`, `group`, `email`
                FROM
                    " . $this->table_name . " 
                WHERE
                    username='".$this->username."'";
                
                // prepare query statement
                $stmt = $this->conn->prepare($query);
                // execute query
                $stmt->execute();
                
                if($stmt->rowCount() > 0){
                        // get retrieved row
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['user_username'] = $row['username'];
                        $_SESSION['user_group'] = $row['group'];
                        $_SESSION['user_name'] = $row['name'];
                        $_SESSION['user_surname'] = $row['surname'];
                        $_SESSION['user_shortname'] = $row['shortname'];
                        $_SESSION['user_email'] = $row['email'];

                        // create array
                        $user_arr=array(
                            "status" => true,
                            "message" => "Добро пожаловать!"            
                        );
                    }
                    

                
            } else {
                $error_text="Неправильный пароль!"; 
                if($this->username=='') $error_text="Выберите пользователя!";
                
                $user_arr=array(
                    "status" => false,
                    "message" => $error_text,
                );
            }
                
                // make it json format
               return json_encode($user_arr);    
            
        }
        
        
    }