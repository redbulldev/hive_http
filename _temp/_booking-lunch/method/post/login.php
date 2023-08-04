<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\QueryException;

$body = json_decode($request->getBody());
$username = !empty($body->username) ? $body->username : '';
$password = !empty($body->password) ? $body->password : '';


try {
    if (!empty($username) && !empty($password)) {
        $res = login($username, $password);
        if ($res) {
            if (!empty($res['fullname']) ) {
                $columns = ['username', 'fullname', 'email', 'mobile', 'notes', 'office_id', 'menu_id', 'default_lunch', 'datecreate', 'datemodified', 'status', 'isdelete'];
                $user = DB::table('users')->where('username', $username)->select($columns)->first();
                if ($user) {
                    if ($user->status == 1) {
                        $dataupdate = ['isdelete' => 0];
                        if ($user->isdelete == 1) {
                            $dataupdate['role_id'] = null;
                            $user->role_id = null;
                        }
                        DB::table('users')->where('username', $username)->update($dataupdate);
                        $user->username = $username;
                    } else throw new Exception('Account is block');
                } else {
                    DB::table('users')->insert([
                        'username' => $username,
                        'email' => !empty($res['email'])? $res['email']: $username.'@hivetech.vn',
                        'datecreate' => date('Y-m-d H:i:s'),
                        'datemodified' => date('Y-m-d H:i:s'),
                        'status' => 1,
                        'isdelete' => 0
                    ]);
                    $user = DB::table('users')->where('username', $username)->select($columns)->first();
                }
                $user->access_token = createToken($conf['timeexpires'] . ' minutes', $conf['secretkey'], $user);
                $user->refresh_token = createToken($conf['timeexpiresrefresh'] . ' minutes', $conf['secretkeyrefresh'], $user);
                $user->expires_in = $conf['timeexpires'] * 60;
                $user->permission = null;
                // if (!empty($user->role_id)) {
                //     $perm = DB::table('role')->where(['status' => 1, 'isdelete' => 0, 'id' => $user->role_id])->first();
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
    } else throw new Exception('Please enter username and password');
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
