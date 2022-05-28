<?php
namespace App\Model;
use App\Controller\AuthController;

class Usuario {
  public static function getUsuariosDisponiveis(int $id = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($id) {
      $sql = 'SELECT u.id, u.nome, u.usuario FROM usuarios u LEFT JOIN dentistas d ON d.id_usuario = u.id WHERE d.id IS NULL OR d.id = :id';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':id', $id);
    }
    else {
      $sql = 'SELECT u.id, u.nome, u.usuario FROM usuarios u LEFT JOIN dentistas d ON d.id_usuario = u.id WHERE d.id IS NULL';
      $stmt = $conn->prepare($sql);
    }

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Usuário não encontrado.");
    }
  }

  public static function getUsuarios($param = null) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);

    if ($param) {
      $sql = 'SELECT id, ativo, administrador, nome, foto, usuario FROM usuarios WHERE nome LIKE :param OR usuario LIKE :param';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':param', '%'. $param .'%');
    }
    else {
      $sql = 'SELECT id, ativo, administrador, nome, foto, usuario FROM usuarios';
      $stmt = $conn->prepare($sql);
    }
    
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Nenhum usuário encontrado.");
    }
  }

  public static function deleteUsuario(int $id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'DELETE FROM usuarios WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return "Usuário excluído com sucesso.";
    }
    else {
      throw new \Exception("Erro ao excluir o usuário.");
    }
  }

  public static function updateUsuario(array $data, int $id) {
    $fileName = $_FILES['foto']['name'];
    $tempPath = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
        
    if (!empty($fileName)) {
      $fileName = date("d_m_y_h_i_s", time()) . $fileName;
      $upload_path = 'upload/';
      $fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

      if (in_array($fileExt, $valid_extensions)) {				
        if ($fileSize < 5000000) { // até '5MB'
          if (!move_uploaded_file($tempPath, $upload_path . $fileName)) {
            throw new \Exception("Erro ao fazer upload da imagem, tente novamente.");
          }
        }
        else {		
          throw new \Exception("Selecione uma imagem de até 5MB.");
        }
      }
      else {		
        throw new \Exception("Tipo de arquivo não aceito. Selecione um arquivo com uma das extensões a seguir: jpeg, jpg, png, gif");
      }

      $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
      $sql = 'UPDATE usuarios SET ativo = :ativo, administrador = :administrador, foto = :foto, nome = :nome, usuario = :usuario WHERE id = :id';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':ativo', $data['ativo']);
      $stmt->bindValue(':administrador', $data['administrador']);
      $stmt->bindValue(':foto', $fileName);
      $stmt->bindValue(':nome', $data['nome']);
      $stmt->bindValue(':usuario', $data['usuario']);
      $stmt->bindValue(':id', $id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return 'Usuário atualizado com sucesso.';
      }
      else {
        throw new \Exception("Erro ao atualizar usuário.");
      }
    }
    else {
      $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
      $sql = 'UPDATE usuarios SET ativo = :ativo, administrador = :administrador, nome = :nome, usuario = :usuario WHERE id = :id';
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(':ativo', $data['ativo']);
      $stmt->bindValue(':administrador', $data['administrador']);
      $stmt->bindValue(':nome', $data['nome']);
      $stmt->bindValue(':usuario', $data['usuario']);
      $stmt->bindValue(':id', $id);
      $stmt->execute();

      if ($stmt->rowCount() > 0) {
        return 'Usuário atualizado com sucesso.';
      }
      else {
        throw new \Exception("Erro ao atualizar usuário.");
      }
    }
  }

  public static function sendUsuario(array $data) {
    $fileName = $_FILES['foto']['name'];
    $tempPath = $_FILES['foto']['tmp_name'];
    $fileSize = $_FILES['foto']['size'];
        
    if (!empty($fileName)) {
      $fileName = date("d_m_y_h_i_s", time()) . $fileName;
      $upload_path = 'upload/';
      $fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION));
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif');

      if (in_array($fileExt, $valid_extensions)) {				
        if ($fileSize < 5000000) { // até '5MB'
          if (!move_uploaded_file($tempPath, $upload_path . $fileName)) {
            throw new \Exception("Erro ao fazer upload da imagem, tente novamente.");
          }
        }
        else {		
          throw new \Exception("Selecione uma imagem de até 5MB.");
        }
      }
      else {		
        throw new \Exception("Tipo de arquivo não aceito. Selecione um arquivo com uma das extensões a seguir: jpeg, jpg, png, gif");
      }
    }
    else {		
      throw new \Exception("Selecione uma imagem para continuar.");	
    }

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'INSERT INTO usuarios (ativo, administrador, foto, nome, usuario, senha) VALUES (:ativo, :administrador, :foto, :nome, :usuario, :senha)';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':ativo', $data['ativo']);
    $stmt->bindValue(':administrador', $data['administrador']);
    $stmt->bindValue(':foto', $fileName);
    $stmt->bindValue(':nome', $data['nome']);
    $stmt->bindValue(':usuario', $data['usuario']);
    $stmt->bindValue(':senha', password_hash($data['senha'], PASSWORD_DEFAULT));
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return 'Usuário cadastrado com sucesso.';
    }
    else {
      throw new \Exception("Erro ao cadastrar usuário.");
    }
  }

  public static function login($data) {
    $usuario = $data['usuario'];
    $senha = $data['senha'];

    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id, nome, usuario, foto, administrador, senha FROM usuarios WHERE ativo = true and usuario = :usuario';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':usuario', $usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt -> fetch(\PDO::FETCH_ASSOC);
      $hash = $row["senha"];

      if (password_verify($senha, $hash)) {
        $token = AuthController::gerarToken($row["id"], $row["usuario"]);

        return array("usuario" => array("id" => $row["id"], "nome" => $row["nome"], "administrador" => (bool) $row["administrador"], "dentista" => (bool) self::usuarioDentista($row["id"]), "foto" => $row["foto"]), "token" => $token);
      }
      else {
        throw new \Exception("Senha incorreta.");
      }

      return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    else {
      throw new \Exception("Usuário não encontrado.");
    }
  }

  public static function getUsuarioAutenticado($id) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT administrador, nome, foto FROM usuarios WHERE id = :id';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $row = $stmt->fetch(\PDO::FETCH_ASSOC);
      
      return array("id" => $id, "nome" => $row["nome"], "administrador" => (bool) $row["administrador"], "dentista" => (bool) self::usuarioDentista($id), "foto" => $row["foto"]);
    }
    
    return false;
  }

  public static function usuarioDentista(int $id_usuario) {
    $conn = new \PDO(DBDRIVE.': host='.DBHOST.'; dbname='.DBNAME, DBUSER, DBPASS);
    $sql = 'SELECT id FROM dentistas WHERE id_usuario = :id_usuario';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':id_usuario', $id_usuario);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      return true;
    }

    return false;
  }
}