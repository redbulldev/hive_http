<?php

use Respect\Validation\Validator as v;
use Illuminate\Database\Capsule\Manager as DB;

if ($data->tech_status !== 0) {
    throwError($container, $request, [
        'tech_notes' => v::length(2, 5000)->notEmpty(),
        'salary_suggested' => v::length(1, 100)->notEmpty()
    ]);

    if (isset($data->level_id)) {
        throwError($container, $request, [
            'level_id' => v::digit()->notEmpty()
        ]);

        $check_level = DB::table('level')->where('id', $data->level_id)->where('isdelete', 0)->count();

        if (!$check_level) {
            throw new Exception('Level not exist');
        }
    }
}

if ($data->tech_status === 0) {
    throwError($container, $request, [
        'tech_notes' => v::length(2, 5000)->notEmpty()
    ]);

    $data->status = $data->tech_status;
} else {
    $data->status = !empty($data->tech_status) ? $data->tech_status : 0;
}

if (!empty($data->tech_notes)) {
    $data->notes = $data->tech_notes;
}

require('cv_review_interview.php');
