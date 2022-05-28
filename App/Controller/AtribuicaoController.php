<?php
namespace App\Controller;
use App\Model\Atribuicao;

class AtribuicaoController {
  public function get($id) {
    if ($id) {
      return Atribuicao::getAtribuicoes($id);
    }
    else {
      throw new \Exception("Informe o dentista.");
    }
  }

  public function especialidades($id) {
    if ($id) {
      return Atribuicao::getEspecialidades($id);
    }
    else {
      throw new \Exception("Informe o convênio.");
    }
  }

  public function send() {
    return Atribuicao::sendAtribuicao((array) json_decode(file_get_contents('php://input')));
    // return (array) json_decode(file_get_contents('php://input'));
  }

  public function delete($id) {
    if ($id) {
      return Atribuicao::deleteAtribuicao($id);
    }
    else {
      throw new \Exception("Informe o convênio.");
    } 
  }
}