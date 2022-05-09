<?php
namespace App\Model;

class Usuario {
  public static function getUsuario(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT ativo, administrador, nome, foto, usuario FROM usuarios WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Usuário não encontrado.");
    }
  }

  public static function getUsuarios() {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT ativo, administrador, nome, foto, usuario FROM usuarios';
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum usuário encontrado.");
    }
  }

  public static function sendUsuario(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO usuarios (ativo, administrador, nome, usuario, senha) VALUES (:ativo, :administrador, :nome, :usuario, :senha)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ativo', $data['ativo']);
    $stmt->bindValue(':administrador', $data['administrador']);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':usuario', $data['usuario']);
    $stmt->bindValue(':senha', password_hash($data['senha'], PASSWORD_DEFAULT));
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Usuário cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar usuário.");
    }
  }

  public static function login($data) {
    $usuario = $data['usuario'];
    $senha = $data['senha'];

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id, administrador, senha FROM usuarios WHERE usuario = :usuario';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':usuario', $usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt -> fetch(\PDO::FETCH_ASSOC);
      $hash = $row["senha"];

      if (password_verify($senha, $hash)) {
        $cargo = self::getCargo($row["administrador"], $row["id"]);
        $_SESSION["cargo"] = $cargo;

        return array("cargo" => $cargo);
      }
      else {
        throw new \Exception("Senha incorreta.");
      }

      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Usuário não encontrado.");
    }
  }

  public static function getCargo(bool $administrador, int $id_usuario) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id FROM dentistas WHERE id_usuario = :id_usuario';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_usuario', $id_usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $administrador ? '1' : '2';
    }
    else {
      return $administrador ? '1' : '3';
    }
  }
}