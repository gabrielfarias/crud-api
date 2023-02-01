<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT, POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'database/connection.php';
include_once 'class/customer.php';
include_once 'class/jwt_utils.php';

$dbclass = new Connection();
$connection = $dbclass->getInstance();
$customer = new Customer($connection);

$bearer_token = get_bearer_token();
$is_jwt_valid = is_jwt_valid($bearer_token);

if ($is_jwt_valid) {
  $method = $_SERVER['REQUEST_METHOD'];
  isset($_SERVER['PATH_INFO']) ? $path = explode("/", substr($_SERVER['PATH_INFO'], 1)) : null;

  switch ($method) {
    case 'PUT':
      $data = json_decode(file_get_contents("php://input"));
      $customer->name = $data->name;
      $customer->cpf = $data->cpf;
      $id_customer = $path[0];

      if ($customer->update($id_customer)) {
        echo '{
          "status": 200,
          "name": "OK",
          "message": "Customer was edit"}';
      } else {
        echo '{
          "status": 400,
          "name": "Bad Request",
          "message": "Unable to edit customer"}';
      }
      break;
    case 'POST':
      $data = json_decode(file_get_contents("php://input"));
      $customer->name = $data->name;
      $customer->cpf = $data->cpf;

      if ($customer->create()) {
        echo '{
          "status": 200,
          "name": "OK",
          "message": "Customer was created"}';
      } else {
        echo '{
          "status": 400,
          "name": "Bad Request",
          "message": "Unable to create customer"}';
      }
      break;
    case 'GET':
      if (isset($path)) {
        $id_customer = $path[0];
        if ($resultCustormer = $customer->read($id_customer)) {
          echo json_encode($resultCustormer);
        } else {
          echo '{
            "status": 400,
            "name": "Bad Request",
            "message": "Customer not found"}';
          break;
        }
      } else {
        if ($resultCustormer = $customer->readAll()) {
          echo json_encode($resultCustormer);
        } else {
          echo '{
            "status": 400,
            "name": "Bad Request",
            "message": "Unable to list customer"}';
          break;
        }
      }
      break;
    case 'DELETE':
      $id_customer = $path[0];
      if ($customer->delete($id_customer)) {
        echo '{
          "status": 200,
          "name": "OK",
          "message": "Customer was delete"}';
      } else {
        echo '{
          "status": 400,
          "name": "Bad Request",
          "message": "Unable to delete customer"}';
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
