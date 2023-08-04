<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Database\Capsule\Manager as DB;

$username = '';
$user = null;
$role = null;
$permission = null;
$login_id = '';
$login_type = 1;
$headers = getallheaders();
$method = $request->getMethod();
$path = $request->getUri()->getPath();
$ignoreUrl = false;
if (
    !in_array($path, !empty($conf['ignore_' . $method]) ? $conf['ignore_' . $method] : [])
    && !in_array($path, !empty($conf['ignore']) ? $conf['ignore'] : [])
) {
    try {
        if (empty($conf)) require(__DIR__ . '/../../config.php');
        if (isset($headers['authorization'])) $headers['Authorization'] = $headers['authorization'];
        if (isset($headers['platform']) && $headers['platform'] == 'ios')  $login_type = 2;
        else $login_type = 3;
        if (isset($headers['Authorization'])) {
            $authorization = $headers['Authorization'];
            if (!empty($authorization)) {
                if (preg_match('/Bearer\s(\S+)/', $authorization, $matches)) {
                    $token = str_replace('"', '', trim($matches[1], '"'));
                    $openId = false;
                    try {
                        $decoded = JWT::decode($token, (isset($secretkey) ? $secretkey : $conf['secretkey']), array('HS256'));
                    } catch (Exception $e) {
                        $openId = true;
                        $publicKey = buildPublicKey((isset($secretkey) ? $secretkey : $conf['secretKeyCloak']));
                        $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
                    }
                    if(!$openId)
                    {
                        if (isset($decoded->sub) && isset($decoded->sub->username) && time() <= $decoded->exp) {
                            $username = $decoded->sub->username;
                            $login_id = $username;
                           
                        } else {
                            throw new Exception('Data from token error');
                        }
                    }else{
                        if (!empty($decoded->preferred_username)) {
                            $username = $decoded->preferred_username;
                            $login_id = $username;
                        } else {
                            throw new Exception('Data from token error');
                        }
                    }
                    $user = DB::table('users')->where('username', $username)->first();
                    if (!$user) {
                        throw new Exception('User not found');
                    }
                } else {
                    throw new Exception('Authorization must be Bearer Token');
                }
            } else {
                throw new Exception('Bearer token not found');
            }
        } else {
            throw new Exception('Authorization not found');
        }
    } catch (Exception $e) {
        throw new Exception(json_encode([
            "message" => $e->getMessage(),
            "status" => 401,
            "code" => "auth"
        ]));
    }
} else {
    $ignoreUrl = true;
}
