<?php
namespace App\Model;

class Dentista {
  public static function getDentistas($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT d.*, u.usuario, u.foto FROM dentistas d LEFT JOIN usuarios u ON d.id_usuario = u.id WHERE d.nome LIKE :param OR u.usuario LIKE :param OR d.cpf LIKE :param OR d.inscricao LIKE :param ORDER BY d.id DESC';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT d.*, u.usuario, u.foto FROM dentistas d LEFT JOIN usuarios u ON d.id_usuario = u.id ORDER BY d.id DESC';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum dentista encontrado.");
    }
  }

  public static function deleteDentista(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM dentistas WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Dentista excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o dentista.");
    }
  }

  public static function updateDentista(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE dentistas SET ativo = :ativo, cor = :cor, id_usuario = :id_usuario, inscricao = :inscricao, cpf = :cpf, nome = :nome, data_nascimento = :data_nascimento, telefone = :telefone, cep = :cep, rua = :rua, numero = :numero, bairro = :bairro, cidade = :cidade, estado = :estado WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ativo', $data['ativo']);
    $stmt->bindValue(':cor', $data['cor']);
    $stmt->bindValue(':id_usuario', $data['id_usuario']);
    $stmt->bindValue(':inscricao', $data['inscricao']);
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
      return 'Dentista atualizado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar dentista.");
    }
  }

  public static function sendDentista(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO dentistas (ativo, cor, id_usuario, inscricao, cpf, nome, data_nascimento, telefone, cep, rua, numero, bairro, cidade, estado) VALUES (:ativo, :cor, :id_usuario, :inscricao, :cpf, :nome, :data_nascimento, :telefone, :cep, :rua, :numero, :bairro, :cidade, :estado)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ativo', $data['ativo']);
    $stmt->bindValue(':cor', $data['cor']);
    $stmt->bindValue(':id_usuario', $data['id_usuario']);
    $stmt->bindValue(':inscricao', $data['inscricao']);
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
      return 'Dentista cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar dentista.");
    }
  }
}