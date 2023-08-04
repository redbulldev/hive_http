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
            $data = json_decode($request->getBody());
             if (!$ignoreUrl)checkRole($permission, $name, 'delete', $data, $username);
            if (file_exists(__DIR__ . '/delete/' . $name . '_before.php')) require(__DIR__ . '/delete/' . $name . '_before.php');
            if (is_array($data)) {
                if (count($data)) {
                    if (colExist($name, 'isdelete')) {
                        DB::table($name)->whereIn($columnpri, $data)->update(['isdelete'=>1]);
                    } else {
                        DB::table($name)->whereIn($columnpri, $data)->delete();
                    }
                    if(!empty($loginid))
                    foreach ($data as $lid) {
                        historySave($login_id, 'delete', $name, $lid, []);
                    }
                    $results = ['status' => 'success', 'time' => time()];
                } else
                    $results = ['status' => 'error', 'message' => 'item in data not found'];
            } else
                $results = ['status' => 'error', 'message' => 'Data must be array'];
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