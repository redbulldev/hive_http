<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\QueryException;

$httpStatus = 200;
$name = str_replace('-', '_', $args['name']);
if (in_array($name, $conf['block'])) {
    $httpStatus = 201;
    $results = ['status' => 'error', 'message' => 'API is Block', 'code' => 'block'];
} else {
    try {
        try {
            if ($name === 'users') $columnpri = 'username';
            else $columnpri = 'id';
            require('./shared/getToken.php');
            $id = $args['id'];
             if (!$ignoreUrl)checkRole($permission, $name, 'delete', [$id], $username);
            if (file_exists(__DIR__ . '/delete/' . $name . '_before.php')) require(__DIR__ . '/delete/' . $name . '_before.php');
            $objold= DB::table($name)->where($columnpri, $id);
            if (colExist($name, 'isdelete')) {
                $objold->where($name.'.isdelete', 0);
            }
            $olddata = $objold->first();
            if (!$olddata) {
                throw new Exception('Data not found');
            }
            if (isset($results)) return $results;
            if (file_exists(__DIR__ . '/delete/' . $name . '.php')) require(__DIR__ . '/delete/' . $name . '.php');
            else {
                if (colExist($name, 'isdelete')) {
                    DB::table($name)->where($columnpri, $id)->update(['isdelete'=>1]);
                } else {
                    DB::table($name)->where($columnpri, $id)->delete();
                }
                $results = ['status' => 'success', 'time' => time()];
            }
            if ($results['status'] == 'success' ){
                if(!empty($loginid))$idlog=historySave($login_id, 'delete', $name, $id, $olddata);
            } 
            else {
                $httpStatus = 201;
            }
            if (file_exists(__DIR__ . '/delete/' . $name . '_after.php')) require(__DIR__ . '/delete/' . $name . '_after.php');
        } catch (QueryException $e) {
            throw new Exception(json_encode([
                "message" => 'Can`t delete this item',
                "status" => 201,
                "code" => "dberror",
                "more" => $e->getMessage()
            ]));
        }
    } catch (Exception $e) {
        $obj = @json_decode($e->getMessage());
        if (is_object($obj)) {
            $httpStatus = $obj->status;
            $results = ['status' => 'error', 'message' => $obj->message, 'code' => $obj->code];
            if (!empty($obj->more)) {
                $results['more'] = $obj->more;
            }
        } else {
            $httpStatus = 201;
            $results = ['status' => 'error', 'message' => $e->getMessage(), 'code' => 'fatalerror'];
        }
    }
}