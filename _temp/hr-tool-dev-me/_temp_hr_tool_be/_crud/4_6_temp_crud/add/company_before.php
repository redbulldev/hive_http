<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request, [
    'name' => v::length(2, 200)->notEmpty()
]);

if (isset($data->name)) {
    if (DB::table($name)->where(['name' => trim($data->name)])->where('isdelete', 0)->count()) {
        throw new Exception('Name already exists');
    }
}

if (isset($data->description)) {
    $data->description = substr($data->description, 0, 500);
}
