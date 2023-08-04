<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request,  [
    'id_criteria_group' =>  v::digit()->notEmpty(),
    'name' => v::length(2, 200)->notEmpty(),
    'status' => v::intVal()->between(0, 1)
]);

if (!empty($data->name)) {
    $check_exsit = DB::table('template_request')
        ->where('name', trim($data->name))
        ->where('isdelete', 0)
        ->count();

    if ($check_exsit) {
        throw new Exception('Name already exist');
    }
}

if (!empty($data->id_criteria_group)) {
    if (!DB::table('criteria_group')->where('id', $data->id_criteria_group)->where('isdelete', 0)->count()) {
        throw new Exception('Criteria Group not exist');
    }
}
