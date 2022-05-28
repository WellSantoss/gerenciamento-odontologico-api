<?php
namespace App\Model;

class Horario {
  public static function getHorarios(int $id_dentista) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id, dia_semana, inicio, termino FROM horarios_atendimento WHERE id_dentista = :id_dentista';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_dentista', $id_dentista);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum horário encontrado.");
    }
  }

  public static function deleteHorario(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM horarios_atendimento WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Horário excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o horário.");
    }
  }

  public static function sendHorario(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO horarios_atendimento (id_dentista, dia_semana, inicio, termino) VALUES (:id_dentista, :dia_semana, :inicio, :termino)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_dentista', $data['id_dentista']);
    $stmt->bindValue(':dia_semana', $data['dia_semana']);
    $stmt->bindValue(':inicio', $data['inicio']);
    $stmt->bindValue(':termino', $data['termino']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Horário cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar o horário.");
    }
  }
}