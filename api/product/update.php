<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PATCH');

$data = json_decode(file_get_contents('php://input'), true);

include_once '../../config/Database.php';
include_once '../../models/Product.php';

$title = trim($data['title']);
$image = trim($data['image']);
$description = trim($data['description']);
$category_id = trim($data['category_id']);
$location = trim($data['location']);
$resale_price = trim($data['resale_price']);
$original_price = trim($data['original_price']);
$years_of_use = trim($data['years_of_use']);

 if (empty($title) && empty($image) && empty($description) && empty($category_id) && empty($location) && empty($resale_price) && empty($original_price)&& empty($years_of_use)) {
    http_response_code(400);
    echo json_encode(array('error'=> 'You must provide update data'));
    exit;
 }

 $db = new Database();
 $conn = $db->connect();
 $product = new Product($conn);
 $res = $product->products_update($data);
  if ($res['status'] === "ok") {
    http_response_code(200);
  } else {
    http_response_code(400);
  }
  echo json_encode($res);
 ?>