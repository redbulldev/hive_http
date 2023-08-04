<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

throwError($container, $request,  [
    'review' => v::length(1, 500)->notEmpty(),
    'status' => v::intVal()->between(0, 1),
]);

if (!empty($id)) {
    $review = DB::table('reviews')->where('id', $id)->where('isdelete', 0)->first();

    if (empty($review)) {
        throw new Exception('Reviews not exist');
    }
}

if (!empty($data->id_cv)) {
    $cv = DB::table('cv')->where('id', $data->id_cv)->where('isdelete', 0)->first();

    if (empty($cv) || $review->id_cv !== $cv->id) {
        throw new Exception('CV not exist');
    }
}
