<?php

use Respect\Validation\Validator as v;
use Illuminate\Database\Capsule\Manager as DB;

throwError($container, $request,  [
    'name' => v::length(2, 100)->notEmpty()
]);

if (!empty($data->name)) {
    $check_exsit = DB::table('criteria')
        ->where('name', $data->name)
        ->where('id', '!=', trim($id))
        ->where('isdelete', 0)
        ->count();

    if ($check_exsit) {
        throw new Exception('Name already exist');
    }
}

// die(':ok');
