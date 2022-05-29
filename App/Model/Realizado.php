<?php
namespace App\Model;

class Realizado {
  public static function getDuracao($id_consulta) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = "SELECT r.id, p.tempo FROM consultas c INNER JOIN procedimentos_realizados r ON r.id_consulta = c.id INNER JOIN procedimentos p ON r.id_procedimento = p.id WHERE c.id = :id_consulta";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_consulta', $id_consulta);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $procedimentos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $data = date('H:i:s', strtotime('00:00:00'));

      foreach ($procedimentos as $procedimento) {
        $duracao = $procedimento['tempo'];
        $arr = explode(':', $duracao);
        $data = date('H:i:s', strtotime("{$data} + {$arr[0]} hours {$arr[1]} minutes {$arr[2]} seconds"));
      }

      return $data;
    }
    else {
      return '00:00:00';
    }
  }

  public static function deleteRealizado($id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = "DELETE FROM procedimentos_realizados WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Procedimento excluído com sucesso.';
    }
    else {
      throw new \Exception("Erro ao excluir o procedimento.");
    }
  }

  public static function getProcedimentosRealizados($id_consulta) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT r.id, r.finalizado, r.dente, r.observacoes, p.procedimento FROM procedimentos_realizados r LEFT JOIN procedimentos p ON r.id_procedimento = p.id WHERE r.id_consulta = :id_consulta';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_consulta', $id_consulta);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum procedimento encontrado.");
    }
  }

  public static function updateProcedimentos(array $procedimento) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE procedimentos_realizados SET finalizado = :finalizado, dente = :dente, observacoes = :observacoes WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':finalizado', $procedimento['finalizado']);
    $stmt->bindValue(':dente', $procedimento['dente']);
    $stmt->bindValue(':observacoes', $procedimento['observacoes']);
    $stmt->bindValue(':id', $procedimento['id']);
    $stmt->execute();

    if (!$stmt->rowCount() > 0) {
      return true;
    }
    else {
      return false;
    }
  }

  public static function sendProcedimento(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO procedimentos_realizados (id_consulta, id_procedimento) VALUES (:id_consulta, :id_procedimento)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_consulta', $data['id_consulta']);
    $stmt->bindValue(':id_procedimento', $data['id_procedimento']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Procedimento cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar o procedimento.");
    }
  }
}