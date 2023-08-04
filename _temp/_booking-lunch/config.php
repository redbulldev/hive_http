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
    $get_domain = $_SERVER['HTTP_HOST'];
} else {
    $get_domain = 'localhost:3002';
}

$domain_local = 'localhost';

$check_domain_local = strlen(strstr($get_domain, $domain_local) > 0);

$check_hr_tool_local = true;

if (!empty($check_domain_local)) {
    require_once(__DIR__ . '/ignore.php');
} else {
    $check_hr_tool_local = false;
}

$domain = str_replace(':', '.', $get_domain);

$domain_staging = 'dev';

$check_domain_staging = strlen(strstr($domain, $domain_staging) > 0);

$domain_product = 'api-lunch';

$check_domain_product = strlen(strstr($domain, $domain_product) > 0);

if (isset($domain) && $check_hr_tool_local === false) {
    try {
        if (file_exists(__DIR__ . '/config.' . $domain . '.php')) {
            require __DIR__ . '/config.' . $domain . '.php';
        } else if (empty($check_domain_staging) && !empty($check_domain_product)) {
            require __DIR__ . '/config.192.168.1.52.7070.php';
        } else if (!empty($check_domain_staging) && empty($check_domain_product)) {
            require __DIR__ . '/config.192.168.1.52.7071.php';
        }
    } catch (Exception $e) {
    }
} else if ($check_hr_tool_local === true) {
    try {
        if ($domain === 'localhost.3002') {
            require __DIR__ . '/config.localhost.3002.php';
        } else if ($domain === 'localhost.3009') {
            require __DIR__ . '/config.localhost.3009.php';
        }
    } catch (Exception $e) {
    }
}
