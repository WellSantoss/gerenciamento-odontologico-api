<?php
header('Content-Type: application/json');
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
      header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT"); // may also be using PUT, PATCH, HEAD etc
  }    

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])) {
      header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
  }

  exit(0);
}

require_once 'vendor/autoload.php';
use App\Controller\AuthController;
use App\Controller\UsuarioController;

$url = explode('/', $_GET['url']);
$controller = 'App\Controller\\'.ucfirst($url[1]).'Controller';
$method = strtolower($url[2]);
$param = array_slice($url, 3);

if ($method == 'login') {
  try {
    $response = UsuarioController::login();;

    http_response_code(200);
    echo json_encode(array('status' => 'success', 'data' => $response), JSON_UNESCAPED_UNICODE);
  } catch (\Exception $e) {
    http_response_code(401);
    echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
  }
}
elseif ($method == 'autentica') {
  try {
    $response = AuthController::checkAuth();

    http_response_code(200);
    echo json_encode(array('status' => 'success', 'data' => $response), JSON_UNESCAPED_UNICODE);
  } catch (\Exception $e) {
    http_response_code(401);
    echo json_encode(array('status' => 'error', 'data' => 'Usuário não autenticado'), JSON_UNESCAPED_UNICODE);
  }
}
else {
  if (AuthController::checkAuth()) {
    try {
      $response = call_user_func_array(array(new $controller, $method), $param);

      http_response_code(200);
      echo json_encode(array('status' => 'success', 'data' => $response), JSON_UNESCAPED_UNICODE);
    } catch (\Exception $e) {
      http_response_code(400);
      echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
    }
  }
  else {
    http_response_code(401);
    echo json_encode(array('status' => 'error', 'data' => 'Usuário não autenticado.'), JSON_UNESCAPED_UNICODE);
  }
}

