<?php

use Illuminate\Database\Capsule\Manager as DB;

$criteria =  DB::table($name)->where('id', $id)->first();

$criteria_elements =  DB::table('criteria_elements')
                        ->where('id_criteria', $id)
                        ->get();

$arr_criteria = [];

foreach ($criteria as $key => $value) {
    $arr_criteria[$key] = $value;
}

foreach ($criteria_elements as $key => $value) {
    $arr_criteria['criteria_elements'][$key] = $criteria_elements[$key];
}

$one = $arr_criteria;
