<?php
namespace App\Model;

class Financas {
  public static function getFinancas($inicio = null, $final = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($inicio && $final) {
      
    }
    else if ($inicio) {
      
    }
    else if ($final) {
      
    }
    else {
      $sql = 'SELECT f.id, u.nome AS usuario, f.operacao, f.tipo, f.valor, f.data, f.descricao FROM financas f INNER JOIN usuarios u ON f.id_usuario = u.id';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return array('resumo' => self::getData($inicio, $final), 'financas' => $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }
    else {
      throw new \Exception("Nenhuma transação encontrada.");
    }
  }

  public static function getData($inicio = null, $final = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($inicio && $final) {
      
    }
    else if ($inicio) {
      
    }
    else if ($final) {
      
    }
    else {
      $sql = "SELECT (SELECT IFNULL(SUM(valor), 0) FROM financas) AS total, (SELECT IFNULL(SUM(valor), 0) FROM financas WHERE operacao = 'Receita') AS receitas, (SELECT IFNULL(SUM(valor), 0) FROM financas WHERE operacao = 'Despesa') AS despesas FROM financas";
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      return array('total' => 0, 'receitas' => 0, 'despesas' => 0);
    }
  }

  public static function deleteFinancas(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM financas WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Transação excluída com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir a transação.");
    }
  }

  public static function updateFinancas(array $data, int $id) {
    if ($data['operacao'] == 'Despesa' && $data['valor'] > 0) {
      $valor = - $data['valor'];
    }
    else if ($data['operacao'] == 'Receita' && $data['valor'] < 0) {
      $valor = abs($data['valor']);
    }
    else {
      $valor = $data['valor'];
    }

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE financas SET operacao = :operacao, tipo = :tipo, valor = :valor, data = :data, descricao = :descricao WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':operacao', $data['operacao']);
    $stmt->bindValue(':tipo', $data['tipo']);
    $stmt->bindValue(':valor', $valor);
    $stmt->bindValue(':data', $data['data']);
    $stmt->bindValue(':descricao', $data['descricao']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Transação atualizada com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar a transação.");
    }
  }

  public static function sendFinancas(array $data) {
    if ($data['operacao'] == 'Despesa' && $data['valor'] > 0) {
      $valor = - $data['valor'];
    }
    else if ($data['operacao'] == 'Receita' && $data['valor'] < 0) {
      $valor = abs($data['valor']);
    }
    else {
      $valor = $data['valor'];
    }

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO financas (id_usuario, operacao, tipo, valor, data, descricao) VALUES (:id_usuario, :operacao, :tipo, :valor, :data, :descricao)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_usuario', $data['id_usuario']);
    $stmt->bindValue(':operacao', $data['operacao']);
    $stmt->bindValue(':tipo', $data['tipo']);
    $stmt->bindValue(':valor', $valor);
    $stmt->bindValue(':data', str_replace('T', ' ', $data['data']));
    $stmt->bindValue(':descricao', $data['descricao']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Transação cadastrada com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar transação.");
    }
  }
}