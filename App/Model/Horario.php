<?php
namespace App\Model;
use App\Model\Consulta;
use App\Model\Realizado;

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

  public static function getHorariosDisponiveis($post) {
    $id_dentista = $post['id_dentista'];
    $data = $post['data'];
    $duracao = $post['duracao'];
    $dia_semana = date('w', strtotime($data)) + 1;

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id, inicio, termino FROM horarios_atendimento WHERE dia_semana = :dia_semana AND id_dentista = :id_dentista';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':dia_semana', $dia_semana);
    $stmt->bindValue(':id_dentista', $id_dentista);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $horarios_atendimento = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      $consultas = Consulta::getConsultasByData($data);
      $horarios = array();

      if (!$consultas) {
        foreach ($horarios_atendimento as $horario) {
          $inicio = new \DateTime($horario['inicio']);
          $termino = new \DateTime($horario['termino']);
          
          while ($inicio < $termino) {
            $data_atual = $inicio->format('H:i:s');
            list($hours, $minutes, $seconds) = sscanf($duracao, '%d:%d:%d');
            $inicio->add(new \DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds)));
            
            if ($inicio <= $termino) {
              array_push($horarios, $data_atual);
            }
          }
        }
      }
      else {
        foreach ($horarios_atendimento as $horario) {
          $horario_inicio = new \DateTime($data . ' ' . $horario['inicio']);
          $final_consulta = new \DateTime($data . ' ' . $horario['inicio']);
          $termino = new \DateTime($data . ' ' . $horario['termino']);
          
          while ($final_consulta < $termino) {
            list($hours, $minutes, $seconds) = sscanf($duracao, '%d:%d:%d');
            $final_consulta->add(new \DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds)));

            $consulta_atual = self::verificaHorario($consultas, $horario_inicio, $final_consulta);

            if ($final_consulta <= $termino && $consulta_atual) {
              array_push($horarios, $horario_inicio->format('H:i:s'));
            }

            $horario_inicio->add(new \DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds)));
          }
        }
      }
      
      return $horarios;
    }
    else {
      throw new \Exception("Nenhum horário disponível.");
    }
  }

  public static function getDuracaoConsulta($data, $id) {
    $data_consulta = new \DateTime($data);
    $final_consulta = new \DateTime($data);
    $duracao_consulta = Realizado::getDuracao($id);
    
    list($hours_consulta, $minutes_consulta, $seconds_consulta) = sscanf($duracao_consulta, '%d:%d:%d');
    $final_consulta->add(new \DateInterval(sprintf('PT%dH%dM%dS', $hours_consulta, $minutes_consulta, $seconds_consulta)));
    
    return array('inicio' => $data_consulta, 'final' => $final_consulta);
  }

  public static function verificaHorario($consultas, $horario_inicio, $horario_final) {
    $consultas_horario = 0;
    
    foreach ($consultas as $i => $consulta) {
      $consulta_atual = self::getDuracaoConsulta($consulta['data'], $consulta['id']);
      
      if ($horario_inicio >= $consulta_atual['final'] || $horario_final <= $consulta_atual['inicio']) {
        if (isset($consultas[$i - 1])) {
          $consulta_anterior = self::getDuracaoConsulta($consultas[$i - 1]['data'], $consultas[$i - 1]['id']);  
          $valida_anterior = $horario_inicio >= $consulta_anterior['final'];
        }
        else {
          $valida_anterior = true;
        }
        
        if (isset($consultas[$i + 1])) {
          $proxima_consulta = self::getDuracaoConsulta($consultas[$i + 1]['data'], $consultas[$i + 1]['id']);  
          $valida_proxima = $horario_final <= $proxima_consulta['inicio'];
        }
        else {
          $valida_proxima = true;
        }

        if (($valida_anterior && $valida_proxima)) {
          $consultas_horario++;
        }
      }
    }

    return ($consultas_horario > 0);
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