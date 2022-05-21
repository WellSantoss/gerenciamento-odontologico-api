<?php
namespace App\Controller;
use App\Model\Financas;

class FinancasController {
  public function get($param = null) {
    if ($param) {
      return Financas::getFinancas($param);
    }
    else {
      return Financas::getFinancas();
    }
  }

  public function send() {
    return Financas::sendFinancas((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Financas::updateFinancas($_POST, $id);
  }

  public function delete($id) {
    if ($id) {
      return Financas::deleteFinancas($id);
    }
    else {
      throw new \Exception("Selecione um usuário para excluir.");
    }
  }
}