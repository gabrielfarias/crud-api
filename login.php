<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'database/connection.php';
include_once 'class/auth.php';
include_once 'class/jwt_utils.php';

$dbclass = new Connection();
$connection = $dbclass->getInstance();
$auth = new Auth($connection);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  $data = json_decode(file_get_contents("php://input", true));
	$auth->email = $data->email;
	$auth->password = $data->password;

	if ($returnAuth = $auth->login()) {
		echo $returnAuth;
	} else {
		echo '{
			"status": 401,
			"name": "Unauthorized",
			"message": "Access denied"}';
	}
} else {
	echo '{
		"status": 405,
		"name": "Method Not Allowed",
		"message": "Method not supported"}';
}