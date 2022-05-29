<?php
namespace App\Model;
use App\Model\Financas;
use App\Model\Realizado;

class Consulta {
  public static function getConsultasByData($data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = "SELECT id, data FROM consultas WHERE DATE(data) = :data";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':data', $data);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      return false;
    }
  }

  public static function getConsultas($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT c.id, u.nome AS usuario, c.id_dentista, d.cor, d.nome AS dentista, p.nome AS paciente, c.data_pagamento, c.data, c.valor, c.pago, c.status FROM consultas c LEFT JOIN dentistas d ON c.id_dentista = d.id LEFT JOIN pacientes p ON c.id_paciente = p.id LEFT JOIN usuarios u ON c.id_usuario = u.id WHERE d.nome LIKE :param OR p.nome LIKE :param OR c.status LIKE :param ORDER BY c.data DESC';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT c.id, u.nome AS usuario, c.id_dentista, d.cor, d.nome AS dentista, p.nome AS paciente, c.data_pagamento, c.data, c.valor, c.pago, c.status FROM consultas c LEFT JOIN dentistas d ON c.id_dentista = d.id LEFT JOIN pacientes p ON c.id_paciente = p.id LEFT JOIN usuarios u ON c.id_usuario = u.id ORDER BY c.data DESC';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhuma consulta encontrada.");
    }
  }

  public static function getConsultasByDentista($id_dentista) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT c.id, u.nome AS usuario, c.id_dentista, d.cor, d.nome AS dentista, p.nome AS paciente, c.data_pagamento, c.data, c.valor, c.pago, c.status FROM consultas c LEFT JOIN dentistas d ON c.id_dentista = d.id LEFT JOIN pacientes p ON c.id_paciente = p.id LEFT JOIN usuarios u ON c.id_usuario = u.id WHERE c.id_dentista = :id_dentista ORDER BY c.data DESC';
    $stmt = $conn->prepare($sql);    
    $stmt->bindValue(':id_dentista', $id_dentista);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhuma consulta encontrada.");
    }
  }

  public static function deleteConsulta(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM consultas WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Consulta excluída com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir a consulta.");
    }
  }

  public static function updateConsulta(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $data_atual = date('Y-m-d H-i-s', time());
    $consulta = json_decode(json_encode($data['consulta']), true);
    $procedimentos = json_decode(json_encode($data['procedimentos']), true);
    $msg = '';

    foreach ($procedimentos as $procedimento) {
      Realizado::updateProcedimentos($procedimento);
    }

    $sql = 'SELECT pago, data_pagamento FROM consultas WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      $dados_consulta = $stmt->fetch(\PDO::FETCH_ASSOC);

      if ($dados_consulta['pago'] == '1' && $consulta['pago'] == '0') {
        if (!Financas::deleteFinancasByData(str_replace('T', ' ', $consulta['data_pagamento']))) {
          $msg = 'Consulta atualizada com sucesso, porém não foi possível excluir a trasação do pagamento.';
        }

        $sql = 'UPDATE consultas SET status = :status, pago = :pago, data_pagamento = :data_pagamento WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':data_pagamento', null);
      }
      else if ($dados_consulta['pago'] == '0' && $consulta['pago'] == '1') {
        $data_financas = array('operacao' => 'Receita', 'valor' => $consulta['valor'], 'id_usuario' => $data['id_usuario'], 'tipo' => 'Pagamento de Consulta', 'data' => $data_atual);

        if (!Financas::sendFinancas($data_financas)) {
          $msg = 'Consulta atualizada com sucesso, porém não foi possível inserir a trasação do pagamento.';
        }

        $sql = 'UPDATE consultas SET status = :status, pago = :pago, data_pagamento = :data_pagamento WHERE id = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':data_pagamento', $data_atual);
      }
      else {
        $sql = 'UPDATE consultas SET status = :status, pago = :pago WHERE id = :id';
        $stmt = $conn->prepare($sql);
      }

      $stmt->bindValue(':status', $consulta['status']);
      $stmt->bindValue(':pago', $consulta['pago']);
      $stmt->bindValue(':id', $id);
      $stmt->execute();

      return $msg != '' ? $msg : 'Consulta atualizado com sucesso.';
    }
    else {
      throw new \Exception("Não foi possível encontrar a consulta.");
    }
  }

  public static function sendConsulta(array $data) {
    $data_atual = date('Y-m-d H-i-s', time());

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO consultas (status, id_dentista, id_paciente, id_usuario, data, valor, pago, data_pagamento) VALUES (:status, :id_dentista, :id_paciente, :id_usuario, :data, :valor, :pago, :data_pagamento)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':status', $data['status']);
    $stmt->bindValue(':id_dentista', $data['id_dentista']);
    $stmt->bindValue(':id_paciente', $data['id_paciente']);
    $stmt->bindValue(':id_usuario', $data['id_usuario']);
    $stmt->bindValue(':data', $data['data'] . ' ' . $data['horario']);
    $stmt->bindValue(':valor', $data['valor']);
    $stmt->bindValue(':pago', $data['pago']);
    $stmt->bindValue(':data_pagamento', $data['pago'] == '1' ? $data_atual : null);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $sql2 = 'SELECT id FROM consultas ORDER BY id DESC LIMIT 1';
      $stmt2 = $conn->prepare($sql2);
      $stmt2->execute();
  
      if ($stmt2->rowCount() > 0) {
        $id_consulta = $stmt2->fetch(\PDO::FETCH_ASSOC)['id'];

        if (!Realizado::sendProcedimento(array('id_consulta' => $id_consulta, 'id_procedimento' => $data['id_procedimento']))) {
          throw new \Exception("Não foi possível cadastrar o procedimento para a consulta agendada.");
        }
      }
      else {
        throw new \Exception("Não foi possível cadastrar o procedimento para a consulta agendada.");
      }

      if ($data['pago'] == '1') {
        $data_financas = array('operacao' => 'Receita', 'valor' => $data['valor'], 'id_usuario' => $data['id_usuario'], 'tipo' => 'Pagamento de Consulta', 'data' => $data_atual);

        if (!Financas::sendFinancas($data_financas)) {
          return 'Consulta agendada com sucesso, porém não foi possível inserir a trasação do pagamento.';
        }
      }

      return 'Consulta agendada com sucesso.';
    }
    else {
      throw new \Exception("Erro ao agendar a consulta.");
    }
  }
}