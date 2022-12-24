<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

$data = json_decode(file_get_contents('php://input'), true);

include_once '../../config/Database.php';
include_once '../../models/User.php';

 $db = new Database();
 $conn = $db->connect();

 $email = strtolower(trim($data['email']));
 $name = trim($data['name']);
 $photo = trim($data['photo']);
 $password = trim($data['password']);
 $role = trim($data['role']);

 $errors = [];
 if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Valid email is required';
 }

 if (empty($name) || !preg_match("/^[a-zA-Z-' ]*$/",$name)) {
    $errors['name'] = 'Valid name is required';
 }

 if (!empty($role) && !($role === 'user' || $role === 'seller')) {
    $errors['role'] = 'Role must be user or seller';
 }

 if (empty($password) || strlen($password) < 6) {
    $errors['Password'] = 'Password must be at least 6 characters long';
  }

  if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode(array('errors'=> $errors));
  } else {
    $user = new User($conn);
    $res = $user->signup_user($data);
    if ($res['data']) {
        http_response_code(200);
    } else {
        http_response_code(401);
    }
    echo json_encode($res);
  }

 

 ?>