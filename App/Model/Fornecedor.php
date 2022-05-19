<?php
namespace App\Model;

class Fornecedor {
  public static function getFornecedor(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT nome, telefone, endereco FROM fornecedores WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Fornecedor não encontrado.");
    }
  }

  public static function getFornecedores($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT id, nome, telefone, endereco FROM fornecedores WHERE nome LIKE :param';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT id, nome, telefone, endereco FROM fornecedores';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum fornecedor encontrado.");
    }
  }

  public static function deleteFornecedor(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM fornecedores WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Fornecedor excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o fornecedor.");
    }
  }

  public static function updateFornecedor(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE fornecedores SET nome = :nome, telefone = :telefone, endereco = :endereco WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':telefone', $data['telefone']);
    $stmt->bindValue(':endereco', $data['endereco']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Fornecedor atualizado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar fornecedor.");
    }
  }

  public static function sendFornecedor(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO fornecedores (nome, telefone, endereco) VALUES (:nome, :telefone, :endereco)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':telefone', $data['telefone']);
    $stmt->bindValue(':endereco', $data['endereco']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Fornecedor cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar fornecedor.");
    }
  }
}