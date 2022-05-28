<?php
namespace App\Model;

class Atribuicao {
  public static function getAtribuicoes(int $id_dentista) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT a.id, e.nome AS especialidade FROM atribuicoes a INNER JOIN especialidades e ON a.id_especialidade = e.id WHERE a.id_dentista = :id_dentista';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_dentista', $id_dentista);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhuma especialidade encontrada.");
    }
  }

  public static function getEspecialidades(int $id_dentista) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT DISTINCT id_especialidade FROM atribuicoes WHERE id_dentista = :id_dentista';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_dentista', $id_dentista);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $atribuicoes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $especialidades = array();

      foreach ($atribuicoes as $key => $value) {
        array_push($especialidades, $value['id_especialidade']);
      }

      $sql = "SELECT id, nome FROM especialidades WHERE id NOT IN (" . join(", ", $especialidades) . ")";
    }
    else {
      $sql = "SELECT id, nome FROM especialidades";
    }
    
    $stmt2 = $conn->prepare($sql);
    $stmt2->execute();

    if ($stmt2->rowCount() > 0) {
      return $stmt2->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Especialidade não encontrada.");
    }
  }

  public static function deleteAtribuicao(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM atribuicoes WHERE id = :id';
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

  public static function sendAtribuicao(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO atribuicoes (id_dentista, id_especialidade) VALUES (:id_dentista, :id_especialidade)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_dentista', $data['id_dentista']);
    $stmt->bindValue(':id_especialidade', $data['id_especialidade']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Especialidade cadastrada com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar a especialidade.");
    }
  }
}