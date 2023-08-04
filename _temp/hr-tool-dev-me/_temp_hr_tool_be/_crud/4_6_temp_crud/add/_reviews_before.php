<?php

use Illuminate\Database\Capsule\Manager as DB;

use Respect\Validation\Validator as v;

throwError($container, $request,  [
    'review' => v::length(1, 500)->notEmpty(),
    'status' => v::intVal()->between(0, 1),
]);


if (!empty($data->id_cv)) {
    if (!DB::table('cv')->where('id', $data->id_cv)->where('isdelete', 0)->count()) {
        throw new Exception('CV not exist');
    }
}


// "id_cv": 13,
// "criterias": [
//       ["1",30],
//       ["2",37],
//       ["5",65]
//   ]