<?php
namespace App\Model;
use App\Model\Produto;

class Retirada {
  public static function getRetiradas($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT pu.id, p.nome AS produto, u.nome AS usuario, pu.quantidade, pu.data FROM produtos_utilizados pu LEFT JOIN produtos p ON pu.id_produto = p.id LEFT JOIN usuarios u ON pu.id_usuario = u.id WHERE p.nome LIKE :param OR u.nome LIKE :param ORDER BY data DESC';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT pu.id, p.nome AS produto, u.nome AS usuario, pu.quantidade, pu.data FROM produtos_utilizados pu LEFT JOIN produtos p ON pu.id_produto = p.id LEFT JOIN usuarios u ON pu.id_usuario = u.id ORDER BY data DESC';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum registro encontrado.");
    }
  }

  public static function deleteRetirada(array $data, int $id) {
    $repor = $data['repor'];
    
    if ($repor == true) {
      $id_produto = $data['produto'];
      $quantidade = $data['quantidade'];

      if (!Produto::updateEstoque($id_produto, $quantidade)) {
        throw new \Exception("Erro ao atualizar estoque do produto.");
      }
    }

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM produtos_utilizados WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Registro excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o registro.");
    }
  }

  public static function sendRetirada(array $data) {
    $id_produto = $data['produto'];
    $quantidade = $data['quantidade'];

    if (!Produto::updateEstoque($id_produto, - $quantidade)) {
      throw new \Exception("Erro ao atualizar estoque do produto.");
    }

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO produtos_utilizados (id_produto, id_usuario, quantidade) VALUES (:id_produto, :id_usuario, :quantidade)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_produto', $id_produto);
    $stmt->bindValue(':id_usuario', $data['usuario']);
    $stmt->bindValue(':quantidade', $quantidade);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Retirada registrada com sucesso.";
    }
    else {
      throw new \Exception("Erro ao registrar a retirada.");
    }
  }
}