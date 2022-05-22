<?php
namespace App\Controller;
use App\Model\Cobertura;

class CoberturaController {
  public function get($id = null) {
    if ($id) {
      return Cobertura::getCoberturas($id);
    }
    else {
      throw new \Exception("Informe o convênio.");
    }
  }

  public function procedimentos($id = null) {
    if ($id) {
      return Cobertura::getProcedimentos($id);
    }
    else {
      throw new \Exception("Informe o convênio.");
    }
  }

  public function send() {
    return Cobertura::sendCobertura((array) json_decode(file_get_contents('php://input')));
  }

  public function delete($id) {
    if ($id) {
      return Cobertura::deleteCobertura($id);
    }
    else {
      throw new \Exception("Informe o convênio.");
    } 
  }
}