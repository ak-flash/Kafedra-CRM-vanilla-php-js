<?php

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
        
        // Создание нового пользователя
        function create() {
            
            // Вставляем запрос
            $query = "INSERT INTO " . $this->table_name . "
                    SET
                        firstname = :firstname,
                        secondname = :secondname,
                        lastname = :lastname,
                        group = :group,
                        email = :email,
                        password = :password";

            // подготовка запроса
            $stmt = $this->conn->prepare($query);

            // привязываем значения
            $stmt->bindParam(':firstname', $this->firstname);
            $stmt->bindParam(':secondname', $this->secondname);
            $stmt->bindParam(':lastname', $this->lastname);
            $stmt->bindParam(':group', $this->group);
            $stmt->bindParam(':email', $this->email);

            // для защиты пароля
            // хешируем пароль перед сохранением в базу данных
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);

            // Выполняем запрос
            // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
            if($stmt->execute()) {
                return true;
            }
            return false;
        }


        function list($filter_group = 0){
            
            $users_list["users"] = array();

            $filter_group_query = '';

            if($filter_group == 'instructors') $filter_group_query = " WHERE `group` IN ('admin', 'instructor') ";

            $query = "SELECT id, firstname, secondname, lastname, email FROM " . $this->table_name . $filter_group_query . " ORDER BY `group` ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            if(!$stmt->execute()) print_r($stmt->errorInfo());
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                $user_item = array(
                    "id" => $row['id'],
                    "email" => $row['email'],
                    "firstname" => $row['firstname'],
                    "secondname" => $row['secondname'],
                    "lastname" => $row['lastname'],
                );
                array_push($users_list["users"], $user_item);
            }
        
            return json_encode($users_list["users"]);
        }      
        
    }