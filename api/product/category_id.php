<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

$data = json_decode(file_get_contents('php://input'), true);

include_once '../../config/Database.php';
include_once '../../models/Product.php';

 $id = trim($data['id']);

 if (empty($id)) {
    http_response_code(400);
    echo json_encode(array('error'=> 'id not provided'));
    exit;
 }

 $db = new Database();
 $conn = $db->connect();
 $product = new Product($conn);
  $res = $product->get_products_by_category($id);
  if ($res['data']) {
    http_response_code(200);
  } else {
    http_response_code(404);
  }
  echo json_encode($res);
 ?>