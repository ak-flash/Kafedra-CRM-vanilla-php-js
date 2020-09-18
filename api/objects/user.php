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

        function login(){
 
            // запрос, чтобы проверить, существует ли электронная почта
            $query = "SELECT * FROM " . $this->table_name . "
                    WHERE email = ?
                    LIMIT 0,1";
         
            // подготовка запроса
            $stmt = $this->conn->prepare($query);
         
            // инъекция
            $this->email=htmlspecialchars(strip_tags($this->email));
         
            // привязываем значение e-mail
            $stmt->bindParam(1, $this->email);
         
            // выполняем запрос
            $stmt->execute();
         
            // получаем количество строк
            $num = $stmt->rowCount();
         
            // если электронная почта существует,
            // присвоим значения свойствам объекта для легкого доступа и использования для php сессий
            if($num>0) {
         
                // получаем значения
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
         
                // присвоим значения свойствам объекта
                $this->id = $row['id'];
                $this->firstname = $row['firstname'];
                $this->secondname = $row['secondname'];
                $this->lastname = $row['lastname'];
                $this->email = $row['email'];
                $this->group = $row['group'];
                $this->password = $row['password'];
         
                // вернём 'true', потому что в базе данных существует электронная почта
                return true;
            }
         
            // вернём 'false', если адрес электронной почты не существует в базе данных
            return $num;
        }


        function list(){
            
            $users_list["users"]=array();
            
            $query = "SELECT firstname, secondname, lastname, email FROM " . $this->table_name . " ORDER BY `group` ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            // execute query
            $stmt->execute();
            
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                $user_item = array(
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