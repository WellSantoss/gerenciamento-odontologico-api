<?php
header('Content-Type: aplication/json');
require_once 'vendor/autoload.php';

if ($_GET['url']) {
  $method = strtolower($_SERVER['REQUEST_METHOD']);
  $url = explode('/', $_GET['url']);
  array_shift($url);
  
  $service = 'App\Service\\'.ucfirst($url[0]).'Service';
  array_shift($url);

  try {
    $response = call_user_func_array(array(new $service, $method), $url);

    http_response_code(200);
    echo json_encode(array('status' => 'success', 'data' => $response), JSON_UNESCAPED_UNICODE);
  } catch (\Exception $e) {
    http_response_code(404);
    echo json_encode(array('status' => 'error', 'data' => $e->getMessage()), JSON_UNESCAPED_UNICODE);
  }
}
