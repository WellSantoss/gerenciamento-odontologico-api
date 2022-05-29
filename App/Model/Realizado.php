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

  public static function sendProcedimento(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO procedimentos_realizados (finalizado, id_consulta, id_procedimento, dente, observacoes) VALUES (:finalizado, :id_consulta, :id_procedimento, :dente, :observacoes)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':finalizado', isset($data['finalizado']) ? $data['finalizado'] : '0');
    $stmt->bindValue(':id_consulta', $data['id_consulta']);
    $stmt->bindValue(':id_procedimento', $data['id_procedimento']);
    $stmt->bindValue(':dente', isset($data['dente']) ? $data['finalizado'] : null);
    $stmt->bindValue(':observacoes', isset($data['observacoes']) ? $data['finalizado'] : null);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Transação cadastrada com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar transação.");
    }
  }
}