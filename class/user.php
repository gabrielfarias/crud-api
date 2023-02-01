<?php
class User
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

  public function create()
  {
    $sql = "SELECT id_user FROM $this->table WHERE email = :email";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':email', $this->email);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      return false;
    } else {
      $sql  = "INSERT INTO $this->table (email, password) VALUES (:email, :password)";
      $stmt = $this->connection->prepare($sql);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':password', $this->password);
      return $stmt->execute();
    }
  }

  public function read($id_user)
  {
    $sql = "SELECT * FROM $this->table WHERE id_user = :id_user";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch();
  }

  public function readAll()
  {
    $sql = "SELECT * FROM $this->table";
    $stmt = $this->connection->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function update($id_user)
  {
    $sql  = "SELECT id_user FROM $this->table WHERE id_user = :id_user";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) <= 0) {
      return false;
    } else {
      $sql2  = "UPDATE $this->table SET email = :email, password = :password WHERE id_user = :id_user";
      $stmt = $this->connection->prepare($sql2);
      $stmt->bindParam(':email', $this->email);
      $stmt->bindParam(':password', $this->password);
      $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
      return $stmt->execute();
    }
  }

  public function delete($id_user)
  {
    $sql = "SELECT * FROM $this->table WHERE id_user = :id_user";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($users) <= 0) {
      
      return false;
    } else {
      $sql2  = "DELETE FROM $this->table WHERE id_user = :id_user";
      $stmt = $this->connection->prepare($sql2);
      $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
      return $stmt->execute();
    }
  }
}
