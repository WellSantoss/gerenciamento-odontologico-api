<?php
namespace App\Controller;
use App\Model\Produto;

class ProdutoController {
  public function get($param = null) {
    if ($param) {
      if (gettype($param) == 'integer') {
        return Produto::getProduto($param);
      }
      else {
        return Produto::getProdutos($param);
      }
    }
    else {
      return Produto::getProdutos();
    }
  }

  public function send() {
    return Produto::sendProduto((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Produto::updateProduto($_POST, $id);
  }

  public function refound($id) {
    return Produto::reporProduto((array) json_decode(file_get_contents('php://input')), $id);
  }

  public function delete($id) {
    if ($id) {
      return Produto::deleteProduto($id);
    }
    else {
      throw new \Exception("Selecione um usuário para excluir.");
    }
  }
}