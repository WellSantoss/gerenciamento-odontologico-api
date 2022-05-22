<?php
namespace App\Model;

class Cobertura {
  public static function getCoberturas(int $id_convenio) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT cp.id, p.procedimento, cp.porcentagem FROM convenios_procedimentos cp INNER JOIN procedimentos p ON cp.id_procedimento = p.id WHERE cp.id_convenio = :id_convenio';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_convenio', $id_convenio);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum procedimento encontrado.");
    }
  }

  public static function getProcedimentos(int $id_convenio) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT DISTINCT id_procedimento FROM convenios_procedimentos WHERE id_convenio = :id_convenio';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_convenio', $id_convenio);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $coberturas = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $procedimentos = array();

      foreach ($coberturas as $key => $value) {
        array_push($procedimentos, $value['id_procedimento']);
      }

      $sql = "SELECT id, procedimento FROM procedimentos WHERE ativo = '1' AND id NOT IN (" . join(", ", $procedimentos) . ")";
    }
    else {
      $sql = "SELECT id, procedimento FROM procedimentos WHERE ativo = '1'";
    }
    
    $stmt2 = $conn->prepare($sql);
    $stmt2->execute();

    if ($stmt2->rowCount() > 0) {
      return $stmt2->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Procedimento não encontrado.");
    }
  }

  public static function deleteCobertura(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM convenios_procedimentos WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Procedimento excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o procedimento.");
    }
  }

  public static function sendCobertura(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO convenios_procedimentos (id_convenio, id_procedimento, porcentagem) VALUES (:id_convenio, :id_procedimento, :porcentagem)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_convenio', $data['convenio']);
    $stmt->bindValue(':id_procedimento', $data['procedimento']);
    $stmt->bindValue(':porcentagem', $data['porcentagem']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Procedimento cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar procedimento.");
    }
  }
}