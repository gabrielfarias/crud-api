<?php
class Customer
{
  private $connection;
  private $table = "customer";

  public $id_customer;
  public $name;
  public $cpf;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }

  public function create()
  {
    $sql  = "INSERT INTO $this->table (name, cpf) VALUES (:name, :cpf)";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':cpf', $this->cpf);
    return $stmt->execute();
  }

  public function read($id_customer)
  {
    $sql = "SELECT * FROM $this->table WHERE id_customer = :id_customer";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':id_customer', $id_customer, PDO::PARAM_INT);
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

  public function update($id_customer)
  {
    $sql  = "UPDATE $this->table SET name = :name, cpf = :cpf WHERE id_customer = :id_customer";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':cpf', $this->cpf);
    $stmt->bindParam(':id_customer', $id_customer, PDO::PARAM_INT);
    return $stmt->execute();
  }

  public function delete($id_customer)
  {
    $sql  = "DELETE FROM $this->table WHERE id_customer = :id_customer";
    $stmt = $this->connection->prepare($sql);
    $stmt->bindParam(':id_customer', $id_customer, PDO::PARAM_INT);
    return $stmt->execute();
  }
}
