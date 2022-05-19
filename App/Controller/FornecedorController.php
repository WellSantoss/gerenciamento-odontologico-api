<?php
namespace App\Controller;
use App\Model\Fornecedor;

class FornecedorController {
  public function get($param = null) {
    if ($param) {
      if (gettype($param) == 'integer') {
        return Fornecedor::getFornecedor($param);
      }
      else {
        return Fornecedor::getFornecedores($param);
      }
    }
    else {
      return Fornecedor::getFornecedores();
    }
  }

  public function send() {
    return Fornecedor::sendFornecedor((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Fornecedor::updateFornecedor($_POST, $id);
  }

  public function delete($id) {
    if ($id) {
      return Fornecedor::deleteFornecedor($id);
    }
    else {
      throw new \Exception("Selecione um usuário para excluir.");
    }
  }
}