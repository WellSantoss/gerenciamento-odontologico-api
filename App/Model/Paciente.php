<?php
namespace App\Model;

class Paciente {
  public static function getPaciente(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT p.*, c.nome AS convenio FROM pacientes p LEFT JOIN convenios c ON p.id_convenio = c.id WHERE p.id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $paciente = $stmt->fetch(\PDO::FETCH_ASSOC);
      $infos = array('id' => $paciente['id'], 'id_convenio' => $paciente['id_convenio'], 'convenio' => $paciente['convenio'], 'nome' => $paciente['nome'], 'cpf' => $paciente['cpf'], 'data_nascimento' => $paciente['data_nascimento'], 'telefone' => $paciente['telefone'], 'cep' => $paciente['cep'], 'rua' => $paciente['rua'], 'numero' => $paciente['numero'], 'bairro' => $paciente['bairro'], 'cidade' => $paciente['cidade'], 'estado' => $paciente['estado']);

      $obs = array('queixa_atual' => $paciente['queixa_atual'], 'medicacoes' => $paciente['medicacoes'], 'alergias' => $paciente['alergias'], 'doencas' => $paciente['doencas'], 'cirurgias' => $paciente['cirurgias'], 'tipo_sangramento' => $paciente['tipo_sangramento'], 'tipo_cicatrizacao' => $paciente['tipo_cicatrizacao'], 'falta_de_ar' => $paciente['falta_de_ar'], 'gestante' => $paciente['gestante'], 'observacoes' => $paciente['observacoes']);

      return array('infos' => $infos, 'obs' => $obs);
    }
    else {
      throw new \Exception("Paciente não encontrado.");
    }
  }

  public static function getDescontoConvenio($id_paciente, $id_procedimento) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = "SELECT cp.porcentagem FROM pacientes p INNER JOIN convenios c ON p.id_convenio = c.id INNER JOIN convenios_procedimentos cp ON cp.id_convenio = c.id WHERE p.id = :id_paciente AND cp.id_procedimento = :id_procedimento";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_paciente', $id_paciente);
    $stmt->bindValue(':id_procedimento', $id_procedimento);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC)['porcentagem'];
    }
    else {
      return 0;
    }
  }

  public static function getPacientes($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT p.id, p.id_convenio, p.cpf, p.nome, p.data_nascimento, p.telefone, p.cep, p.rua, p.numero, p.bairro, p.cidade, p.estado, c.nome AS convenio FROM pacientes p LEFT JOIN convenios c ON p.id_convenio = c.id WHERE p.nome LIKE :param OR c.nome LIKE :param OR p.cpf LIKE :param ORDER BY p.id DESC';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT p.id, p.id_convenio, p.cpf, p.nome, p.data_nascimento, p.telefone, p.cep, p.rua, p.numero, p.bairro, p.cidade, p.estado, c.nome AS convenio FROM pacientes p LEFT JOIN convenios c ON p.id_convenio = c.id ORDER BY p.id DESC';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum paciente encontrado.");
    }
  }

  public static function deletePaciente(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM pacientes WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Paciente excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o paciente.");
    }
  }

  public static function updatePaciente(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE pacientes SET id_convenio = :id_convenio, cpf = :cpf, nome = :nome, data_nascimento = :data_nascimento, telefone = :telefone, cep = :cep, rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_convenio', $data['id_convenio'] != '' ? $data['id_convenio'] : null);
    $stmt->bindValue(':cpf', $data['cpf']);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':data_nascimento', $data['data_nascimento']);
    $stmt->bindValue(':telefone', $data['telefone']);
    $stmt->bindValue(':cep', $data['cep']);
    $stmt->bindValue(':rua', $data['rua']);
    $stmt->bindValue(':numero', $data['numero']);
    $stmt->bindValue(':bairro', $data['bairro']);
    $stmt->bindValue(':cidade', $data['cidade']);
    $stmt->bindValue(':estado', $data['estado']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Paciente atualizado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar paciente.");
    }
  }

  public static function updateObsPaciente(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE pacientes SET queixa_atual = :queixa_atual, medicacoes = :medicacoes, alergias = :alergias, doencas = :doencas, cirurgias = :cirurgias, tipo_sangramento = :tipo_sangramento, tipo_cicatrizacao = :tipo_cicatrizacao, falta_de_ar = :falta_de_ar, gestante = :gestante, observacoes = :observacoes WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':queixa_atual', $data['queixa_atual']);
    $stmt->bindValue(':medicacoes', $data['medicacoes']);
    $stmt->bindValue(':alergias', $data['alergias']);
    $stmt->bindValue(':doencas', $data['doencas']);
    $stmt->bindValue(':cirurgias', $data['cirurgias']);
    $stmt->bindValue(':tipo_sangramento', $data['tipo_sangramento']);
    $stmt->bindValue(':tipo_cicatrizacao', $data['tipo_cicatrizacao']);
    $stmt->bindValue(':falta_de_ar', $data['falta_de_ar']);
    $stmt->bindValue(':gestante', $data['gestante']);
    $stmt->bindValue(':observacoes', $data['observacoes']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Paciente atualizado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar paciente.");
    }
  }

  public static function sendPaciente(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO pacientes (id_convenio, cpf, nome, data_nascimento, telefone, cep, rua, numero, bairro, cidade, estado) VALUES (:id_convenio, :cpf, :nome, :data_nascimento, :telefone, :cep, :rua, :numero, :bairro, :cidade, :estado)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_convenio', $data['id_convenio'] != '' ? $data['id_convenio'] : null);
    $stmt->bindValue(':cpf', $data['cpf']);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':data_nascimento', $data['data_nascimento']);
    $stmt->bindValue(':telefone', $data['telefone']);
    $stmt->bindValue(':cep', $data['cep']);
    $stmt->bindValue(':rua', $data['rua']);
    $stmt->bindValue(':numero', $data['numero']);
    $stmt->bindValue(':bairro', $data['bairro']);
    $stmt->bindValue(':cidade', $data['cidade']);
    $stmt->bindValue(':estado', $data['estado']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $sql2 = 'SELECT id FROM pacientes ORDER BY id DESC LIMIT 1';
      $stmt2 = $conn->prepare($sql2);
      $stmt2->execute();
  
      if ($stmt2->rowCount() > 0) {
        $row = $stmt2->fetch(\PDO::FETCH_ASSOC);

        return array('id' => $row['id'], 'msg' => 'Paciente cadastrado com sucesso.');
      }
      else {
        return 'Paciente cadastrado com sucesso.';
      }
    }
    else {
      throw new \Exception("Erro ao cadastrar paciente.");
    }
  }
}