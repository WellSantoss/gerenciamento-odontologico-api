<?php
namespace App\Model;

class Usuario {
  public static function getUsuario(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT * FROM usuarios WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum usuário encontrado");
    }

  }

  public static function getUsuarios() {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT * FROM usuarios';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum usuário encontrado");
    }
  }
}