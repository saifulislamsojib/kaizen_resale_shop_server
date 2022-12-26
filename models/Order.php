<?php declare(strict_types=1);
    class Order {
        // db_stuff
        private $conn;
        private $table = 'orders';
        private $product_table = 'products';
        private $user_table = 'users';

        // User properties
        public $id;
        public $product_id;
        public $order_location;
        public $phone;
        public $buyer_id;
        public $created_at;

        public function __construct(\mysqli $db) {
            $this->conn = $db;
        }

        function place_order($order) {
            $query = "INSERT INTO $this->table (product_id, order_location, phone, buyer_id) 
            VALUES ('$order[product_id]', '$order[order_location]', '$order[phone]', '$order[buyer_id]';";
            
            if ($this->conn->query($query)) {
                return array('message'=> "Product added successfully", 'data'=> $order);
            }
            $error = $this->conn->error;
            return array('message'=> "Product not added", 'error' => $error);
        }
    }
 ?>