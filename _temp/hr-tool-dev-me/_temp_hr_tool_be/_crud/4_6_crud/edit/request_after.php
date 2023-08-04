<?php

use Illuminate\Database\Capsule\Manager as DB;

if (isset($data->levels) && is_array($data->levels) && count($data->levels) > 0) {
    DB::table('request_level')->where('request_id', $id)->delete();

    foreach ($data->levels as $level) {
        $pradd = ['level_id' => $level->id, 'request_id' => $id];
        DB::table('request_level')->insert($pradd);
    }
}

if (!DB::table('criteria_group')->where('id', $data->id_criteria_group)->where(['isdelete' => 0, 'status' => 1])->count()) {
    throw new Exception('Criteria group not exist');
}

if (!empty($data->criterias) && is_array($data->criterias) && count($data->criterias) > 0) {
    $count = 0;

    foreach ($data->criterias as $criteria_id) {
        if (isset($data->criterias[$key][0]) && isset($data->criterias[$key][1])) {
            if (empty($data->criterias[$key][0])) {
                $count++;
            }

            if (!empty($data->criterias[$key][0])) {
                if (!DB::table('criteria')->where('id', $data->criterias[$key][0])->where('isdelete', 0)->count()) {
                    $count++;
                }
            }
        }
    }

    if ($count > 0) {
        throw new Exception('Criteria not exist');
    }

    if ($count === 0) {
        DB::table('criteria_request')->where(['id_request' => $id])->delete();

        foreach ($data->criterias as $key => $item) {
            if (isset($data->criterias[$key][0]) && isset($data->criterias[$key][1])) {
                $pradd = [
                    'id_request' => $id,
                    'id_criteria_group' => $data->id_criteria_group,
                    'id_criteria' => $data->criterias[$key][0],
                    'range' => $data->criterias[$key][1]
                ];

                DB::table('criteria_request')->insert($pradd);
            }
        }
    }
}
