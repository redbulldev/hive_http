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
        if ($name === 'users') $columnpri = 'username';
        else $columnpri = 'id';
        require('./shared/getToken.php');
        $id = $args['id'];
        checkRole($permission, $name, 'edit', [$id], $username);
        try {

            $data = json_decode($request->getBody());
            if ($data && is_object($data)) {
                $data->datemodified = date('Y-m-d H:i:s');
            }
            if (file_exists(__DIR__ . '/edit/' . $name . '_before.php')) require(__DIR__ . '/edit/' . $name . '_before.php');
            $objold= DB::table($name)->where($columnpri, $id);
            if (colExist($name, 'isdelete')) {
                $objold->where($name.'.isdelete', 0);
                unset($data->isdelete);
            }
            $olddata = $objold->first();
            if (!$olddata) {
                throw new Exception('Data not found');
            }
            if (isset($results)) return $results;
            if (file_exists(__DIR__ . '/edit/' . $name . '.php')) require(__DIR__ . '/edit/' . $name . '.php');
            else {
                $newdata = removecolumn($name, $data);
                if (count($newdata) > 0) {
                    DB::table($name)->where($columnpri, $id)->update($newdata);
                    $results = ['status' => 'success', 'time' => date('Y-m-d H:i:s')];
                } else
                    $results = ['status' => 'error', 'message' => 'no data update', 'code' => 'nodataupdate'];
            }
            if (file_exists(__DIR__ . '/edit/' . $name . '_after.php')) require(__DIR__ . '/edit/' . $name . '_after.php');
            if ($results['status'] == 'success') $idlog = historySave($login_id, 'update', $name, $id, $olddata);
            else {
                $httpStatus = 201;
            }
            if(!empty($alertmore)) $results['more'] = $alertmore;
        } catch (QueryException $e) {
            throw new Exception(json_encode([
                "message" => 'Update error',
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
