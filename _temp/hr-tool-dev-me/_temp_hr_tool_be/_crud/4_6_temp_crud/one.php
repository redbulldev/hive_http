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
        $id = $args['id'];
        require('./shared/getToken.php');
        if(!$ignoreUrl)checkRole($permission, $name, 'view', [$id], $username);
        try {
            $params = $request->getQueryParams();
            if (file_exists(__DIR__ . '/one/' . $name . '_before.php')) require(__DIR__ . '/one/' . $name . '_before.php');
            if (file_exists(__DIR__ . '/one/' . $name . '.php')) {
                require(__DIR__ . '/one/' . $name . '.php');
            } else {
                if ($name === 'users') $columnpri = 'username';
                else $columnpri = 'id';
                $objold= DB::table($name)->where($columnpri, $id);
                if (colExist($name, 'isdelete')) {
                    $objold->where($name.'.isdelete', 0);
                }
                $one = $objold->first();
            }
            if (empty($results)) {
                if (!empty($one)) {
                    if (file_exists(__DIR__ . '/one/' . $name . '_after.php')) require(__DIR__ . '/one/' . $name . '_after.php');
                    $results = ['status' => 'success', 'data' => $one, 'time' => time()];
                } else {
                    $results = ['status' => 'error', 'message' => 'Data not found', 'code' => 'datanotfound'];
                    $httpStatus = 201;
                }
            }
        } catch (QueryException $e) {
            throw new Exception(json_encode([
                "message" => 'Error connection '/*.$e->getMessage()*/,
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