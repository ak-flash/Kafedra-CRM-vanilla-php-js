<?php

class Auth {
     
        // database connection and table name
        private $conn;
        private $table_name = "authsessions";
     
        // object properties
        public $id;
        public $user_id;
        public $auth_code;
        public $session_id;
        public $user_agent;
        public $ip;
        public $firstname;
        public $last_login;

        // constructor with $db as database connection
        public function __construct($db){
            $this->conn = $db;
        }
        
        // Generate, insert in base and send by email auth_code
        function send_authcode($user_email, $user_firthname) {
            
            $ip = get_client_ip();
            // Check if we already generated auth_code
            $query_extra = "SELECT auth_code FROM " . $this->table_name . "
                                WHERE user_id = ? AND used = 0";
            $stmt_extra = $this->conn->prepare($query_extra);
            $stmt_extra->bindParam(1, $this->user_id);
            $stmt_extra->execute();
            $num = $stmt_extra->rowCount();
      
            if($num>0) {
                // получаем значения
                $row_extra = $stmt_extra->fetch(PDO::FETCH_ASSOC);
                $authcode_otp = $row_extra['auth_code'];
            
            } else {
                
                $authcode_otp = rand(100000, 999999);
                // Вставляем запрос
                $query = "INSERT INTO " . $this->table_name . "
                        SET
                            user_id = :user_id,
                            auth_code = :auth_code,
                            user_agent = :user_agent,
                            ip = :ip";

                // подготовка запроса
                $stmt = $this->conn->prepare($query);
                if(isset($_SERVER['HTTP_USER_AGENT'])){
                    $this->user_agent = htmlspecialchars(strip_tags($_SERVER['HTTP_USER_AGENT']));
                } else {
                    $this->user_agent = 'n/a';
                }
                    
                
                // привязываем значения
                $stmt->bindParam(':user_id', $this->user_id);
                $stmt->bindParam(':auth_code', $authcode_otp);
                $stmt->bindParam(':user_agent', $this->user_agent);
                $stmt->bindParam(':ip', $ip);

                // Выполняем запрос
                // Если выполнение успешно, то информация о пользователе будет сохранена в базе данных
                if($stmt->execute()) {
                    $this->session_id = $this->conn->lastInsertId();
                }
            }
            
            
            if(isset($authcode_otp)) {
                include_once('mail.php');
            
                $subject = 'Необходима авторизация устройства';
                $body = 'Здравствуйте, <b>'.$user_firthname.'</b><br><br>Ваша учетная запись недавно была использована для входа на этом устройстве: '.$this->user_agent.' (IP: '.$ip.')<br><br><b>Ваш проверочный код</b>: <h2>'.$authcode_otp.'</h2>';
                
                send_email($user_email, $subject, $body);
                
                return true;
            }
            
            return false;
        }

        function login(){
 
            // запрос, чтобы проверить, существует ли электронная почта
            $query = "SELECT * FROM users
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
                $this->user_id = $row['id'];
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


        function check_authcode(){

            $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? AND auth_code = ?";
            // prepare query statement
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(1, $this->user_id);
            $stmt->bindParam(2, $this->auth_code);
            // execute query
            $stmt->execute();
            
            $num = $stmt->rowCount();
            
            if($num>0) { 
                // получаем значения
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                
                $query_update = "UPDATE
                        " . $this->table_name . "
                SET
                    ip = :ip,
                    used = :used
                WHERE
                    id = :id";
                
                $ip = get_client_ip();
                $used_times = $row['used']+1;
                $stmt_update = $this->conn->prepare($query_update);
                $stmt_update->bindParam(':id', $row['id']);
                $stmt_update->bindParam(':ip', $ip);
                $stmt_update->bindParam(':used', $used_times);
                $stmt_update->execute();

                return true;
            }

            return false;
        }    
        
        function list_sessions($user_id = 0){
            
            $data_arr = array();
            $data_arr["sessions"] = array();
            
            $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? ORDER BY last_login ASC";
            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $user_id, PDO::PARAM_INT);

            // execute query
            $stmt->execute();
    
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
                $data_item=array(
                    "session_id" => $row['id'],
                    "user_agent" => $row['user_agent'],
                    "ip" => $row['ip'],
                    "last_login" => date("H:i d/m/Y" ,strtotime($row['updated_at'])),
                );
                array_push($data_arr["sessions"], $data_item);  
            }

            return json_encode($data_arr);
        }

        // delete login session
        function revoke_session($session_id){         
            // query to insert record
            $query = "DELETE FROM
                        " . $this->table_name . "
                    WHERE
                        id = ?";
        
            // prepare query
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $session_id, PDO::PARAM_INT);
            // execute query
            if($stmt->execute()){
                return true;
            }
            return false;
        }
        
    }