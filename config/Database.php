<?php declare(strict_types=1);
    class Database {
        private $host = 'localhost';
        private $db_name = 'kaizen_resale_shop';
        private $username = 'root';
        private $password = '';

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