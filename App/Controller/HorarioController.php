<?php
namespace App\Controller;
use App\Model\Horario;

class HorarioController {
  public function get($id = null) {
    if ($id) {
      return Horario::getHorarios($id);
    }
    else {
      throw new \Exception("Informe o dentista.");
    }
  }

  public function getdisponiveis() {
    return Horario::getHorariosDisponiveis((array) json_decode(file_get_contents('php://input')));
  }

  public function send() {
    return Horario::sendHorario((array) json_decode(file_get_contents('php://input')));
  }

  public function delete($id) {
    if ($id) {
      return Horario::deleteHorario($id);
    }
    else {
      throw new \Exception("Informe o convênio.");
    } 
  }
}