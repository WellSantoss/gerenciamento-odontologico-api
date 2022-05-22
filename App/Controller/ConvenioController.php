<?php
namespace App\Controller;
use App\Model\Convenio;

class ConvenioController {
  public function get($param = null) {
    if ($param) {
      if (gettype($param) == 'integer') {
        return Convenio::getConvenio($param);
      }
      else {
        return Convenio::getConvenios($param);
      }
    }
    else {
      return Convenio::getConvenios();
    }
  }

  public function send() {
    return Convenio::sendConvenio((array) json_decode(file_get_contents('php://input')));
  }

  public function update($id) {
    return Convenio::updateConvenio($_POST, $id);
  }

  public function delete($id) {
    if ($id) {
      return Convenio::deleteConvenio($id);
    }
    else {
      throw new \Exception("Selecione um usuário para excluir.");
    }
  }
}