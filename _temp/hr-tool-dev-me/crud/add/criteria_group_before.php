<?php 

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request,  [
    'name' => v::length(2, 200)->notEmpty(),
    'status' => v::intVal()->between(0, 1),
]);

if (!empty($data->name)) {
    $check_exsit = DB::table('criteria_group')
        ->where('name', trim($data->name))
        ->where('isdelete', 0)
        ->count();

    if ($check_exsit) {
        throw new Exception('Name already exist');
    }
}





// die();
