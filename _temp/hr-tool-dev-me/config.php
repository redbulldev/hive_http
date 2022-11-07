<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type, Authorization, Origin, X-Requested-With, Accept, asselect, search,*');
header('Access-Control-Allow-Methods: POST,GET,OPTIONS,PUT,DELETE');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
}
if (isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])) {
    $domain = $_SERVER['HTTP_HOST'];
} else $domain = 'localhost:3001';
$domain = str_replace(':','.',$domain);
//echo $domain;
if (isset($domain)) {
    try {
        if($domain == 'localhost:3002') {
            require __DIR__ . '/config.localhost.3002.php';
        }elseif (file_exists(__DIR__ . '/config.' . $domain . '.php')) {
            
            require __DIR__ . '/config.' . $domain . '.php';
        }
    } catch (Exception $e) {
    }
}
