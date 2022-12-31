<?php declare(strict_types=1);
    class Database {
        private $host = 'localhost';
        private $db_name = 'id20083757_kaizenresale';
        private $username = 'id20083757_kaizen';
        private $password = 'XkP8Uu6SA-86OXn~';

        function connect() {
            $conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            // Check connection
            if (mysqli_connect_error()) {
                die("Database connection failed: " . mysqli_connect_error());
            }
            echo "Connected successfully";
            return $conn;
        }
    }
?>