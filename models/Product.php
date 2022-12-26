<?php declare(strict_types=1);
    class Product {
        // db_stuff
        private $conn;
        private $table = 'products';
        private $user_table = 'users';

        // User properties
        public $id;
        public $title;
        public $image;
        public $description;
        public $category_id;
        public $location;
        public $resale_price;
        public $original_price;
        public $years_of_use;
        public $seller_id;
        public $created_at;
        public $status;
        public $views;

        public function __construct(\mysqli $db) {
            $this->conn = $db;
        }

        function add_product($product) {
            $query = "INSERT INTO $this->table (title, image, description, category_id, location, resale_price, original_price, years_of_use, seller_id) 
            VALUES ('$product[title]', '$product[image]', '$product[description]', '$product[category_id]', '$product[location]', '$product[resale_price]', '$product[original_price]', '$product[years_of_use]', '$product[seller_id]');";
            
            if ($this->conn->query($query)) {
                return array('message'=> "Product added successfully", 'data'=> $product);
            }
            $error = $this->conn->error;
            return array('message'=> "Product not added", 'error' => $error);
        }

        function get_product_by_id($id) {
            $view_query = "UPDATE $this->table SET views = views + 1  WHERE id = '$id'";
            $this->conn->query($view_query);
            $query = "SELECT products.*, users.name AS seller_name, users.email AS seller_email, users.photo AS seller_photo
            FROM $this->table 
            INNER JOIN $this->user_table ON $this->table.seller_id = $this->user_table.id 
            and $this->table.id='$id';";

            $result = $this->conn->query($query);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    return array('data' => $row);
                 }
            }
            return array('message' => "The Product not found");
        }

        function get_products_by_category($id) {
            $query = "SELECT products.*, users.name AS seller_name, users.email AS seller_email, users.photo AS seller_photo
            FROM $this->table 
            INNER JOIN $this->user_table ON $this->table.seller_id = $this->user_table.id 
            and $this->table.category_id='$id';";

            $result = $this->conn->query($query);

            if ($result->num_rows > 0) {
                $data = array();
                while($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                 }
                 return array('data' => $data);
            }
            return array('message' => "No product found");
        }

        function get_products_by_user_id($id) {
            $query = "SELECT products.*, users.name AS seller_name, users.email AS seller_email, users.photo AS seller_photo
            FROM $this->table 
            INNER JOIN $this->user_table ON $this->table.seller_id = $this->user_table.id 
            and $this->table.seller_id='$id';";
            
            $result = $this->conn->query($query);

            if ($result->num_rows > 0) {
                $data = array();
                while($row = $result->fetch_assoc()) {
                    array_push($data, $row);
                 }
                 return array('data' => $data);
            }
            return array('message' => "No product found");
        }

        function products_update($product) {
            $id = $product['id'];
            $title = $product['title'];
            $image = $product['image'];
            $description = $product['description'];
            $category_id = $product['category_id'];
            $location = $product['location'];
            $resale_price = $product['resale_price'];
            $original_price = $product['original_price'];
            $years_of_use = $product['years_of_use'];

            $query = "UPDATE $this->table SET";

            if (!empty($title)) {
                $query .= " title = '$title'";
            }
            if (!empty($image)) {
                $query .= str_contains($query, '=') ? ", image = '$image'" : " image = '$image'";
            }
            if (!empty($description)) {
                $query .= str_contains($query, '=') ? ", description = '$description'" : " description = '$description'";
            }
            if (!empty($category_id)) {
                $query .= str_contains($query, '=') ? ", category_id = '$category_id'" : " category_id = '$category_id'";
            }
            if (!empty($location)) {
                $query .= str_contains($query, '=') ? ", location = '$location'" : " location = '$location'";
            }
            if (!empty($resale_price)) {
                $query .= str_contains($query, '=') ? ", resale_price = '$resale_price'" : " resale_price = '$resale_price'";
            }
            if (!empty($original_price)) {
                $query .= str_contains($query, '=') ? ", original_price = '$original_price'" : " original_price = '$original_price'";
            }
            if (!empty($years_of_use)) {
                $query .= str_contains($query, '=') ? ", years_of_use = '$years_of_use'" : " years_of_use = '$years_of_use'";
            }
            $query .= " WHERE id='$id'";

            $this->conn->query($query);
            return array('message' => 'Product updated successfully', 'status'=> "ok");
        }
    }
 ?>