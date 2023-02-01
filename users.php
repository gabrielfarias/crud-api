<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'database/connection.php';
include_once 'class/user.php';
include_once 'class/jwt_utils.php';

$dbclass = new Connection();
$connection = $dbclass->getInstance();
$user = new User($connection);

$bearer_token = get_bearer_token();
$is_jwt_valid = is_jwt_valid($bearer_token);


if ($is_jwt_valid) {
  $method = $_SERVER['REQUEST_METHOD'];
  isset($_SERVER['PATH_INFO']) ? $path = explode("/", substr($_SERVER['PATH_INFO'], 1)) : null;

  switch ($method) {
    case 'PUT':
      $data = json_decode(file_get_contents("php://input"));
      $user->email = $data->email;
      $user->password = $data->password;
      $id_user = $path[0];
      
      if ($user->update($id_user)) {
        echo '{
          "status": 200,
          "name": "OK",
          "message": "User was edit"}';
      } else {
        echo '{
          "status": 400,
          "name": "Bad Request",
          "message": "Unable to edit user"}';
      }
      break;
    case 'POST':
      $data = json_decode(file_get_contents("php://input"));
      $user->email = $data->email;
      $user->password = $data->password;

      if ($user->create()) {
        echo '{
          "status": 200,
          "name": "OK",
          "message": "User was created"}';
      } else {
        echo '{
          "status": 400,
          "name": "Bad Request",
          "message": "Unable to create user"}';
      }
      break;
    case 'GET':
      if (isset($path)) {
        $id_user = $path[0];
        if ($resultUser = $user->read($id_user)) {
          echo json_encode($resultUser);
        } else {
          echo '{
            "status": 400,
            "name": "Bad Request",
            "message": "User not found"}';
          break;
        }
      } else {
        if ($resultUser = $user->readAll()) {
          echo json_encode($resultUser);
        } else {
          echo '{
            "status": 400,
            "name": "Bad Request",
            "message": "Unable to list user"}';
          break;
        }
      }
      break;
    case 'DELETE':
      $id_user = $path[0];
      if ($user->delete($id_user)) {
        echo '{
          "status": 200,
          "name": "OK",
          "message": "User was delete"}';
      } else {
        echo '{
          "status": 400,
          "name": "Bad Request",
          "message": "Unable to delete user"}';
      }
      break;
    default:
      echo '{
        "status": 405,
        "name": "Method Not Allowed",
        "message": "Method not supported"}';
      break;
  }
} else {
  echo '{
    "status": 401,
    "name": "Unauthorized",
    "message": "Access denied"}';
}
