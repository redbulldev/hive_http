<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\QueryException;

$body = json_decode($request->getBody());
$refresh_token = !empty($body->refresh_token) ? $body->refresh_token : '';
try {

    if (!empty($refresh_token)) {
        $token = $refresh_token;
        $decoded = JWT::decode($token, (isset($secretkey) ? $secretkey : $conf['secretkeyrefresh']), array('HS256'));
        if (isset($decoded->sub) && isset($decoded->sub->username) && time() <= $decoded->exp) {
            $username = $decoded->sub->username;
            $user = DB::table('users')->where('username', $username)->first();
            if ($user) {
                if ($user->status == 0) throw new Exception('Account is block');
            } else {
                DB::table('users')->insert([
                    'username' => $username,
                    'email' => $username . '@hivetech.vn',
                    'datecreate' => date('Y-m-d H:i:s'),
                    'datemodified' => date('Y-m-d H:i:s'),
                    'status' => 1
                ]);
                $user = DB::table('users')->where('username', $username)->first();
            }
            $user->access_token = createToken($conf['timeexpires'] . ' minutes', $conf['secretkey'], $user);
            $user->refresh_token = createToken($conf['timeexpiresrefresh'] . ' minutes', $conf['secretkeyrefresh'], $user);
            $user->expires_in = $conf['timeexpires'] * 60;
            // $user->permission = null;
            // if (!empty($user->role_id)) {
            //     $perm = DB::table('role')->where(['status' => 1, 'id' => $user->role_id])->first();
            //     if ($perm && !empty($perm->permission)) {
            //         $user->permission = @json_decode($perm->permission);
            //     }
            // }
            // if (empty($user->permission)) {
            //     throw new Exception('You dont have any permission');
            // }
            $results = ['status' => 'success', 'data' => $user, 'time' => date('Y-m-d H:i:s')];
        } else throw new Exception('Data from token error');
    } else throw new Exception('Error login LDAP');
} catch (Exception $e) {
    $obj = @json_decode($e->getMessage());
    if (is_object($obj)) {
        $httpStatus = $obj->status;
        $results = ['status' => 'error', 'message' => $obj->message, 'code' => $obj->code];
    } else {
        $httpStatus = 201;
        $results = ['status' => 'error', 'message' => $e->getMessage(), 'code' => 'auth'];
    }
}
