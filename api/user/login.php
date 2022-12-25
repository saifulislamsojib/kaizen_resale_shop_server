<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

$data = json_decode(file_get_contents('php://input'), true);

include_once '../../config/Database.php';
include_once '../../models/User.php';

 $email = strtolower(trim($data['email']));
 $password = trim($data['password']);

 $user = new User($conn);
 
 $errors = [];
 if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Valid Email is required';
 }

 if (empty($password) || strlen($password) < 6) {
    $errors['Password'] = 'Password must be at least 6 characters long';
  }

  if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode(array('errors'=> $errors));
    exit;
  }
   $db = new Database();
   $conn = $db->connect();
    $res = $user->login_user($data['email'], $data['password']);
    if ($res['data']) {
       http_response_code(200);
    } else {
       http_response_code(401);
    }
    echo json_encode($res);
 ?>