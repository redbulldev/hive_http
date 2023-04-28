<?php

use Illuminate\Database\Capsule\Manager as DB;

$criteria_group =  DB::table($name)->where('id', $id)->first();

$template_request =  DB::table('template_request')
    ->where('id_criteria_group', $id)
    ->where('isdelete', 0)
    ->get();

$arr_criteria_group = [];

foreach ($criteria_group as $key => $value) {
    $arr_criteria_group[$key] = $value;
}

foreach ($template_request as $key => $value) {
    $arr_criteria_group['templates'][$key] = $template_request[$key];
}

$one = $arr_criteria_group;
