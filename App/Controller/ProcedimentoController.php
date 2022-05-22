<?php
namespace App\Controller;
use App\Model\Procedimento;

class ProcedimentoController {
  public function get($param = null) {
    if ($param) {
      if (gettype($param) == 'integer') {
        return Procedimento::getProcedimento($param);
      }
      else {
        return Procedimento::getProcedimentos($param);
      }
    }
    else {
      return Procedimento::getProcedimentos();
    }
  }

  public function send() {
    return Procedimento::sendProcedimento((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Procedimento::updateProcedimento($_POST, $id);
  }

  public function delete($id) {
    if ($id) {
      return Procedimento::deleteProcedimento($id);
    }
    else {
      throw new \Exception("Selecione um usuário para excluir.");
    }
  }
}