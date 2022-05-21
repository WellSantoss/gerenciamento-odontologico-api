<?php
namespace App\Model;
use App\Model\Financas;

class Produto {
  public static function getProduto(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT p.id, p.nome, p.estoque, p.valor_unitario, p.id_fornecedor f.nome AS fornecedor FROM produtos p INNER JOIN fornecedores f ON p.id_fornecedor = f.id WHERE p.id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Produto não encontrado.");
    }
  }

  public static function getProdutos($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT p.id, p.nome, p.estoque, p.valor_unitario, p.id_fornecedor, f.nome AS fornecedor FROM produtos p INNER JOIN fornecedores f ON p.id_fornecedor = f.id WHERE p.nome LIKE :param OR f.nome LIKE :param';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT p.id, p.nome, p.estoque, p.valor_unitario, p.id_fornecedor, f.nome AS fornecedor FROM produtos p INNER JOIN fornecedores f ON p.id_fornecedor = f.id';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum produto encontrado.");
    }
  }

  public static function deleteProduto(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM produtos WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Produto excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o produto.");
    }
  }

  public static function updateProduto(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE produtos SET nome = :nome, estoque = :estoque, valor_unitario = :valor_unitario, id_fornecedor = :id_fornecedor WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':estoque', $data['estoque']);
    $stmt->bindValue(':valor_unitario', $data['valor_unitario']);
    $stmt->bindValue(':id_fornecedor', $data['id_fornecedor']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Produto atualizado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar produto.");
    }
  }

  public static function updateEstoque(int $id, int $quantidade) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE produtos SET estoque = estoque + :quantidade WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':quantidade', $quantidade);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return true;
    }
    else {
      return false;
    }
  }

  public static function reporProduto(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT nome, valor_unitario, estoque FROM produtos WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $produto = $stmt->fetch(\PDO::FETCH_ASSOC);
      $quantidade = $data['quantidade'];
      $usuario = $data['usuario'];
      $estoque = $produto['estoque'] + $quantidade;
      $valor = $produto['valor_unitario'] * $quantidade;
      $descricao = 'Compra de ' . $quantidade . ' ' . $produto['nome'];

      $tz = 'America/Sao_Paulo';
      $timestamp = time();
      $dt = new \DateTime("now", new \DateTimeZone($tz));
      $dt->setTimestamp($timestamp);
      $currentDate = $dt->format('Y-m-d H:i:s');

      $transacaoData = array('id_usuario' => $usuario, 'operacao' => 'Despesa', 'tipo' => 'Compra de Produtos', 'valor' => $valor, 'data' => $currentDate, 'descricao' => $descricao);

      if (Financas::sendFinancas($transacaoData)) {
        $conn2 = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
        $sql2 = 'UPDATE produtos SET estoque = :estoque WHERE id = :id';
        $stmt2 = $conn2->prepare($sql2);
        $stmt2->bindValue(':estoque', $estoque);
        $stmt2->bindValue(':id', $id);
        $stmt2->execute();

        if ($stmt2->rowCount() > 0) {
          return 'Estoque atualizado com sucesso.';
        }
        else {
          throw new \Exception("Erro ao atualizar estoque do produto.");
        }
      }
      else {
        throw new \Exception("Erro ao cadastrar a transação da reposição de estoque.");
      }
    }
    else {
      throw new \Exception("Produto não encontrado.");
    }
  }

  public static function sendProduto(array $data) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO produtos (nome, estoque, valor_unitario, id_fornecedor) VALUES (:nome, :estoque, :valor_unitario, :id_fornecedor)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':estoque', $data['estoque']);
    $stmt->bindValue(':valor_unitario', $data['valor']);
    $stmt->bindValue(':id_fornecedor', $data['fornecedor']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Produto cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar produto.");
    }
  }
}