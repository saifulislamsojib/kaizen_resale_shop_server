<?php declare(strict_types=1);
    class User {
        // db_stuff
        private $conn;
        private $table = 'users';

        // User properties
        public $id;
        public $name;
        public $email;
        public $photo;
        public $password;
        public $role;
        public $created_at;

        public function __construct(\mysqli $db) {
            $this->conn = $db;
        }

        public function signup_user($user) {
            $already_query = "SELECT id 
            FROM $this->table 
            WHERE email='$user[email]';";
            $already = $this->conn->query($already_query);

            if ($already->num_rows > 0) {
                return array('message'=> "Email is already in use");
            }

            // Hash the password
            $hash = password_hash($user['password'], PASSWORD_DEFAULT);


            $query = "INSERT INTO $this->table (name, email, photo, password, role) 
            VALUES ('$user[name]', '$user[email]', '$user[photo]', '$hash', '$user[role]');";
            
            if ($this->conn->query($query)) {
                return array('message'=> "New user created successfully", 'data'=> $user);
            } else {
                $error = $this->conn->error;
                return array('message'=> "New user not created", 'error' => $error);
            }
        }

        public function login_user(string $email, string $password) {
            $query = "SELECT id, name, email, photo, password, role, created_at 
            FROM $this->table 
            WHERE email='$email';";
            $result = $this->conn->query($query);
            $output = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                   if (password_verify($password, $row['password'])) {
                    $data = array(
                        'id'=>$row['id'],
                        'name'=>$row['name'],
                        'email'=>$row['email'],
                        'photo'=>$row['photo'],
                        'role'=>$row['role'],
                        'created_at'=>$row['created_at']
                    );
                    $output['message'] = "User login successfully";
                    $output['data'] = $data;
                   } else {
                    $output['message'] = "Email or password not matched";
                   }
                }
            } else {
                $output['message'] = "Email or password not matched";
            }
            return $output;
        }

        public function update_user($user) {
            $user_id = $user['id'];
            $name = $user['name'];
            $email = $user['email'];
            $photo = $user['photo'];
            $role = $user['role'];
            $password = $user['password'];
            $pre_password = $user['pre_password'];

            $query = "UPDATE $this->table SET";

            if (!empty($name)) {
                $query .= " name = '$name'";
            }
            if (!empty($email)) {
                $query .= str_contains($query, '=') ? ", email = '$email'" : " email = '$email'";
            }
            if (!empty($photo)) {
                $query .= str_contains($query, '=') ? ", photo = '$photo'" : " photo = '$photo'";
            }
            if (!empty($role)) {
                $query .= str_contains($query, '=') ? ", role = '$role'" : " role = '$role'";
            }
            if (!empty($password)) {

                $pre_pass_query = "SELECT password 
                FROM $this->table 
                WHERE id='$user[id]';";
                $res = $this->conn->query($pre_pass_query);

                if ($res->num_rows > 0) {
                    while($row = $res->fetch_assoc()) {
                        if (password_verify($pre_password, $row['password'])) {
                            $hash = password_hash($password, PASSWORD_DEFAULT);
                            $query .= str_contains($query, '=') ? ", password = '$hash'" : " password = '$hash'";
                        } else {
                            return array('message'=> "Previous password not valid");
                        }
                     }
                } else {
                    return array('message'=> "User not valid");
                }
            }
            $query .= " WHERE id = $user_id";

            $this->conn->query($query);
            return array('message' => 'User data updated successfully', 'status'=> "ok");
        }

        public function get_users_by_role($role){
            $query = "SELECT id, name, email, photo, role
            FROM $this->table 
            WHERE role='$role';";
            $result = $this->conn->query($query);

            if ($result->num_rows > 0) {
                $data = array();
                while($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                 }
                 return array('data' => $data);
            }
            return array('message' => "No user found");
        }
    } 
 ?>