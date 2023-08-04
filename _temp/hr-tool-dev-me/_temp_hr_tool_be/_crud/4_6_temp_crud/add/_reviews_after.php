<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($data->id_criteria)) {
    if (!DB::table('criteria')->where('id', $data->id_criteria)->where('isdelete', 0)->count()) {
        throw new Exception('Criteria not exist');
    }
}

if (!empty($id)) {
    DB::table('criteria_review')->insertGetId([
        'id_criteria' => trim($data->id_criteria),
        'id_review' => $id,
        'range' => $data->range
    ]);
}
