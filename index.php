<?php
header('Content-Type: application/json');
require_once 'vendor/autoload.php';
use App\Controller\Auth;

if ($_GET['url'] && Auth::checkAuth()) {
  $url = explode('/', $_GET['url']);
  $controller = 'App\Controller\\'.ucfirst($url[1]).'Controller';
  $method = strtolower($url[2]);
  $param = array_slice($url, 3);

  try {
    $response = call_user_func_array(array(new $controller, $method), $param);

    http_response_code(200);
    echo json_encode(array('status' => 'success', 'data' => $response), JSON_UNESCAPED_UNICODE);
  } catch (\Exception $e) {
    http_response_code(404);
    echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
  }
}
else {
  http_response_code(404);
  echo json_encode(array('status' => 'error', 'data' => 'Não autenticado.'), JSON_UNESCAPED_UNICODE);
}
