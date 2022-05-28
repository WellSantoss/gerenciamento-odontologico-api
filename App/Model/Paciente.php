<?php
namespace App\Model;

class Paciente {
  public static function getObsPaciente(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT queixa_atual, medicacoes, alergias, doencas, cirurgias, tipo_sangramento, tipo_cicatrizacao, falta_de_ar, gestante, observacoes FROM pacientes WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Não foi possível obter as informações do paciente.");
    }
  }

  public static function getPacientes($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT p.id, p.id_convenio, p.cpf, p.nome, p.data_nascimento, p.telefone, p.cep, p.rua, p.numero, p.bairro, p.cidade, p.estado, c.nome AS convenio FROM pacientes p LEFT JOIN convenios c ON p.id_convenio = c.id WHERE p.nome LIKE :param OR c.nome LIKE :param OR p.cpf LIKE :param';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT p.id, p.id_convenio, p.cpf, p.nome, p.data_nascimento, p.telefone, p.cep, p.rua, p.numero, p.bairro, p.cidade, p.estado, c.nome AS convenio FROM pacientes p LEFT JOIN convenios c ON p.id_convenio = c.id';
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
    $stmt->bindValue(':id_convenio', $data['id_convenio']);
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
    $stmt->bindValue(':id_convenio', $data['id_convenio']);
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
      return 'Paciente cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar paciente.");
    }
  }
}