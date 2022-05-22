<?php
namespace App\Model;

class Convenio {
  public static function getConvenio(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT nome, telefone, endereco FROM convenios WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Convênio não encontrado.");
    }
  }

  public static function getConvenios($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT id, nome, telefone, endereco FROM convenios WHERE nome LIKE :param';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT id, nome, telefone, endereco FROM convenios';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum convênio encontrado.");
    }
  }

  public static function deleteConvenio(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM convenios WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Convênio excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o convênio.");
    }
  }

  public static function updateConvenio(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE convenios SET nome = :nome, telefone = :telefone, endereco = :endereco WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':telefone', $data['telefone']);
    $stmt->bindValue(':endereco', $data['endereco']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Convênio atualizado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar convênio.");
    }
  }

  public static function sendConvenio(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO convenios (nome, telefone, endereco) VALUES (:nome, :telefone, :endereco)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':telefone', $data['telefone']);
    $stmt->bindValue(':endereco', $data['endereco']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Convênio cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar convênio.");
    }
  }
}