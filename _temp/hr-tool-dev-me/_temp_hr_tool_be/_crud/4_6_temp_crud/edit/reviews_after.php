<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request,  [
    'range' => v::intVal()->between(1, 100)
]);

if (!empty($data->id_criteria)) {
    if (!DB::table('criteria')->where('id', $data->id_criteria)->where('isdelete', 0)->count()) {
        throw new Exception('Criteria not exist');
    }
}

if (!empty($id)) {
    DB::table('criteria_review')
        ->where('id_criteria', $data->id_criteria)
        ->where('id_review', $id)
        ->update([
            'range' => $data->range
        ]);
}
