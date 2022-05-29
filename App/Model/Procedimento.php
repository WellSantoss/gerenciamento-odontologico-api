<?php
namespace App\Model;
use App\Model\Paciente;

class Procedimento {
  public static function getProcedimentosAtivos($id_paciente) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = "SELECT id, id_especialidade, procedimento, valor, tempo FROM procedimentos WHERE ativo = '1'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $procedimentos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
      
      foreach ($procedimentos as $key => $procedimento) {
        $desconto = Paciente::getDescontoConvenio($id_paciente, $procedimento['id']);
        $valor = ((100 - $desconto) / 100) * $procedimento['valor'];

        $procedimentos[$key]['desconto'] = number_format((float) $desconto, 2, '.', '');
        $procedimentos[$key]['valor_descontado'] = number_format((float) $valor, 2, '.', '');
      }

      return $procedimentos;
    }
    else {
      throw new \Exception("Nenhum procedimento encontrado.");
    }
  }

  public static function getProcedimentos($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT p.id, p.ativo, p.id_especialidade, e.nome AS especialidade, p.procedimento, p.descricao, p.tempo, p.valor FROM procedimentos p LEFT JOIN especialidades e ON p.id_especialidade = e.id WHERE e.nome LIKE :param OR p.procedimento LIKE :param OR p.descricao LIKE :param';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT p.id, p.ativo, p.id_especialidade, e.nome AS especialidade, p.procedimento, p.descricao, p.tempo, p.valor FROM procedimentos p LEFT JOIN especialidades e ON p.id_especialidade = e.id';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum procedimento encontrado.");
    }
  }

  public static function getProcedimentosDentistas($id_dentista) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT p.id, p.procedimento FROM procedimentos p INNER JOIN especialidades e ON p.id_especialidade = e.id INNER JOIN atribuicoes a ON a.id_especialidade = e.id INNER JOIN dentistas d ON a.id_dentista = d.id WHERE d.id = :id_dentista';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_dentista', $id_dentista);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum procedimento encontrado.");
    }
  }

  public static function deleteProcedimento(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM procedimentos WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Procedimento excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o procedimento.");
    }
  }

  public static function updateProcedimento(array $data, int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'UPDATE procedimentos SET ativo = :ativo, id_especialidade = :id_especialidade, procedimento = :procedimento, descricao = :descricao, tempo = :tempo, valor = :valor WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ativo', $data['ativo']);
    $stmt->bindValue(':id_especialidade', $data['especialidade'] != '' ? $data['especialidade'] : null);
    $stmt->bindValue(':procedimento', $data['procedimento']);
    $stmt->bindValue(':descricao', $data['descricao']);
    $stmt->bindValue(':tempo', $data['tempo']);
    $stmt->bindValue(':valor', $data['valor']);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Procedimento atualizado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao atualizar o procedimento.");
    }
  }

  public static function sendProcedimento(array $data) {
    // $especialidade = ;


    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO procedimentos (ativo, id_especialidade, procedimento, descricao, tempo, valor) VALUES (:ativo, :id_especialidade, :procedimento, :descricao, :tempo, :valor)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ativo', $data['ativo']);
    
    $stmt->bindValue(':id_especialidade', $data['especialidade'] != '' ? $data['especialidade'] : null);
    $stmt->bindValue(':procedimento', $data['procedimento']);
    $stmt->bindValue(':descricao', $data['descricao']);
    $stmt->bindValue(':tempo', $data['tempo']);
    $stmt->bindValue(':valor', $data['valor']);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Procedimento cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar o procedimento.");
    }
  }
}