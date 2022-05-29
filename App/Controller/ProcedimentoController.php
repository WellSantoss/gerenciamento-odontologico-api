<?php
namespace App\Controller;
use App\Model\Procedimento;

class ProcedimentoController {
  public function getativos($id_paciente) {
    return Procedimento::getProcedimentosAtivos($id_paciente);
  }

  public function getall($param = null) {
    if ($param) {
      return Procedimento::getProcedimentos($param);
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