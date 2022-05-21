<?php
namespace App\Model;

class Especialidade {
  public static function getEspecialidade(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id, nome FROM especialidades WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Especialidade não encontrada.");
    }
  }

  public static function getEspecialidades($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT id, nome FROM especialidades WHERE nome LIKE :param';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT id, nome FROM especialidades';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhuma especialidade encontrada.");
    }
  }

  public static function deleteEspecialidade(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM especialidades WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Especialidade excluída com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir a especialidade.");
    }
  }

  public static function updateEspecialidade(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE especialidades SET nome = :nome WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Especialidade atualizada com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar a especialidade.");
    }
  }

  public static function sendEspecialidade(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO especialidades (nome) VALUES (:nome)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Especialidade cadastrada com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar a especialidade.");
    }
  }
}