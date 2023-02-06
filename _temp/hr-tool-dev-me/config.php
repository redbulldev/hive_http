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


////
// echo $_SERVER['SERVER_NAME'].';'; => api-staging-hrm.ossigroup.net;
// echo $_SERVER['SERVER_PORT'].';'; => 80;
// echo $domain; => api-staging-hrm...
// die($domain); => api-staging-






# echo 'PHP_SELF: '.$_SERVER['PHP_SELF'].'; ';
# echo 'GATEWAY_INTERFACE: '.$_SERVER['GATEWAY_INTERFACE'].'; ';
# echo 'SERVER_ADDR: '.$_SERVER['SERVER_ADDR'].'; ';
# echo 'SERVER_NAME: '.$_SERVER['SERVER_NAME'].'; ';
# echo 'SERVER_SOFTWARE: '.$_SERVER['SERVER_SOFTWARE'].'; ';
# echo 'SERVER_PROTOCOL: '.$_SERVER['SERVER_PROTOCOL'].'; ';
# echo 'REQUEST_METHOD: '.$_SERVER['REQUEST_METHOD'].'; ';
# echo 'REQUEST_TIME: '.$_SERVER['REQUEST_TIME'].'; ';
# echo 'QUERY_STRING: '.$_SERVER['QUERY_STRING'].'; ';
# echo 'HTTP_ACCEPT: '.$_SERVER['HTTP_ACCEPT'];
# echo 'HTTP_ACCEPT_CHARSET: '.$_SERVER['HTTP_ACCEPT_CHARSET'] .'; ';    
# echo 'HTTP_HOST: '.$_SERVER['HTTP_HOST'].'; ';
# echo 'HTTP_REFERER: '.$_SERVER['HTTP_REFERER'].'; ';
# echo 'HTTPS: '.$_SERVER['HTTPS'].'; ';
# echo 'REMOTE_ADDR: '.$_SERVER['REMOTE_ADDR'].'; ';
# echo 'REMOTE_HOST: '.$_SERVER['REMOTE_HOST'].'; ';
# echo 'REMOTE_PORT: '.$_SERVER['REMOTE_PORT'].'; ';
# echo 'SCRIPT_FILENAME: '.$_SERVER['SCRIPT_FILENAME'].'; ';
# echo 'SERVER_ADMIN: '.$_SERVER['SERVER_ADMIN'].'; ';
# echo 'SERVER_PORT: '.$_SERVER['SERVER_PORT'].'; ';
# echo 'SERVER_SIGNATURE: '.$_SERVER['SERVER_SIGNATURE'].'; ';
# echo 'SCRIPT_NAME: '.$_SERVER['SCRIPT_NAME'].'; ';
# echo 'SCRIPT_URI: '.$_SERVER['SCRIPT_URI'].'; ';

// ket qua tuong ung
// ------------------------------
// PHP_SELF: /index.php; 
// GATEWAY_INTERFACE: CGI/1.1; 
// SERVER_ADDR: 172.17.0.3; 
// SERVER_NAME: api-staging-hrm.ossigroup.net; 
// SERVER_SOFTWARE: Apache/2.4.53 (Debian); 
// SERVER_PROTOCOL: HTTP/1.1; 
// REQUEST_METHOD: GET; 
// REQUEST_TIME: 1675309864; 
// QUERY_STRING: limit=10&page=1; HTTP_ACCEPT: */*; <br />
// <b>Warning</b>:  Undefined array key "HTTP_ACCEPT_CHARSET" in <b>/var/www/html/config.php</b> on line <b>37</b><br />
// HTTP_ACCEPT_CHARSET: ; 
// HTTP_HOST: api-staging-hrm.ossigroup.net; 
// HTTP_REFERER: https://staging-hrm.ossigroup.net/; <br />
// <b>Warning</b>:  Undefined array key "HTTPS" in <b>/var/www/html/config.php</b> on line <b>43</b><br />
// HTTPS: ; 
// REMOTE_ADDR: 192.168.3.141; <br />
// <b>Warning</b>:  Undefined array key "REMOTE_HOST" in <b>/var/www/html/config.php</b> on line <b>47</b><br />
// REMOTE_HOST: ; REMOTE_PORT: 33324; SCRIPT_FILENAME: /var/www/html/index.php; 
// SERVER_ADMIN: webmaster@localhost; 
// SERVER_PORT: 80; 
// SERVER_SIGNATURE: <address>Apache/2.4.53 (Debian) Server at api-staging-hrm.ossigroup.net Port 80</address>;
// SCRIPT_NAME: /index.php; <br />
// <b>Warning</b>:  Undefined array key "SCRIPT_URI" in <b>/var/www/html/config.php</b> on line <b>61</b><br />
// SCRIPT_URI: ; ok





# $ip_address = gethostbyname("www.api-staging-hrm.ossigroup.net");  
# echo "IP Address of staging is - ".$ip_address;  

# $sdip = gethostbyname("api-staging-hrm.ossigroup.net");
#     $sddomain = gethostbyaddr($sdip);
#     print "IP: $sdip\n";
#     print "Domain: $sddomain\n";