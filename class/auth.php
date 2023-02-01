<?php
class Auth
{
	private $connection;
	private $table = "user";

	public $id_user;
	public $email;
	public $password;

	public function __construct($connection)
	{
		$this->connection = $connection;
	}

	public function login()
	{
		$sql  = "SELECT * FROM $this->table WHERE BINARY email = :email 
		AND BINARY password = :password";
		$stmt = $this->connection->prepare($sql);
		$stmt->bindValue(':email', $this->email);
		$stmt->bindParam(':password', $this->password);
		$stmt->execute();

		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if (count($users) <= 0) {
			return false;
		} else {
			$user = $users[0];
			$email = $user['email'];

			$headers = array('alg' => 'HS256', 'typ' => 'JWT');
			$payload = array('email' => $email, 'exp' => (time() + 360));

			$jwt = generate_jwt($headers, $payload);

			return json_encode(array('token' => $jwt));
			//return $user;
		}
	}
}
