<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

$data = json_decode(file_get_contents('php://input'), true);

include_once '../../config/Database.php';
include_once '../../models/Order.php';

 $product_id = trim($data['product_id']);
 $order_location = trim($data['order_location']);
 $phone = trim($data['phone']);
 $buyer_id = trim($data['buyer_id']);

 if (empty($product_id) || empty($order_location) || empty($phone) || empty($buyer_id)) {
    http_response_code(400);
    echo json_encode(array('error'=> 'One or some value not provided'));
    exit;
 }

 $db = new Database();
 $conn = $db->connect();
 $product = new Order($conn);
  $res = $product->place_order($data);
  if ($res['data']) {
    http_response_code(200);
  } else {
    http_response_code(400);
  }
  echo json_encode($res);

 ?>