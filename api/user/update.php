<?php

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PATCH');

$data = json_decode(file_get_contents('php://input'), true);

include_once '../../config/Database.php';
include_once '../../models/User.php';

 $email = strtolower(trim($data['email']));
 $name = trim($data['name']);
 $photo = trim($data['photo']);
 $password = trim($data['password']);
 $pre_password = trim($data['pre_password']);
 $role = trim($data['role']);

 $errors = [];
 if (empty($email) && empty($password) && empty($pre_password) && empty($role) && empty($name) && empty($photo)) {
    $errors[] = 'You must provide update data';
 }
 if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Valid email is required';
 }

 if (!empty($name) && !preg_match("/^[a-zA-Z-' ]*$/",$name)) {
    $errors['name'] = 'Valid name is required';
 }

 if (!empty($role) && !($role === 'user' || $role === 'seller')) {
    $errors['role'] = 'Role must be user or seller';
 }

 if (!empty($password) && strlen($password) < 6) {
    $errors['Password'] = 'Password must be at least 6 characters long';
  }

 if (!empty($password) && (empty($pre_password) || strlen($pre_password) < 6)) {
    $errors['pre_password'] = 'Previous password must be at least 6 characters long';
  }

  if (count($errors) > 0) {
    http_response_code(400);
    echo json_encode(array('errors'=> $errors));
    exit;
  }

    $db = new Database();
    $conn = $db->connect();
    $user = new User($conn);
    $res = $user->update_user($data);
    if ($res['status'] === "ok") {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    echo json_encode($res);
 ?>