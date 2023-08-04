<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($data->criterias) && is_array($data->criterias) && count($data->criterias) > 0) {
    $count = 0;

    foreach ($data->criterias as $criteria_id) {
        if (empty($criteria_id)) {
            $count++;
        }

        if (!empty($criteria_id)) {
            if (!DB::table('criteria')->where('id', $criteria_id)->where('isdelete', 0)->count()) {
                $count++;
            }
        }
    }

    if ($count > 0) {
        throw new Exception('Criteria not exist');
    }

    if ($count === 0) {
        foreach ($data->criterias as $criteria_id) {
            $pradd = ['id_criteria_group' => $id, 'id_criteria' => $criteria_id];

            $status = DB::table('criteria_has_group')->insert($pradd);
        }
    }
}
