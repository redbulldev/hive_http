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
$arrpath = explode('/', trim($request->getUri()->getPath(), '/\\'));
if (count($arrpath) < 2) throw new Exception('URL Error');
$path = $arrpath[0] . '/' . $arrpath[1];
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
                    $decoded = JWT::decode($token, (isset($secretkey) ? $secretkey : $conf['secretkey']), array('HS256'));
                    //print_r($decoded);
                    if (isset($decoded->sub) && isset($decoded->sub->username) && time() <= $decoded->exp) {
                        $username = $decoded->sub->username;
                        $login_id = $username;
                        $user = DB::table('users')->where('username', $username)->first();
                        if ($user) {
                            $role = DB::table('role')->where(['status' => 1, 'id' => $user->role_id])->first();
                            if ($role) {
                                $permission = @json_decode($role->permission);
                                if (!$permission) {
                                    throw new Exception('Permission not found');
                                }
                            } else {
                                throw new Exception('Role not found');
                            }
                        } else {
                            throw new Exception('User not found');
                        }
                    } else {
                        throw new Exception('Data from token error');
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
}else{
    $ignoreUrl=true;
}