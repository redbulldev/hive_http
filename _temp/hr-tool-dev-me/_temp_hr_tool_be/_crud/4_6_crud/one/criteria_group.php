<?php

use Illuminate\Database\Capsule\Manager as DB;

$criteria_group =  DB::table($name)
    ->where('id', $id)
    ->first();

$criterias =  DB::table('criteria_group')
    ->leftJoin('criteria_has_group', 'criteria_has_group.id_criteria_group', '=', 'criteria_group.id')
    ->where(function ($query) use ($id) {
        $query->where([
            'criteria_group.id' => $id,
            'criteria_group.isdelete' => 0
        ]);
    })
    ->leftJoin('criteria', 'criteria.id', '=', 'criteria_has_group.id_criteria')
    ->where(function ($query) {
        $query->where('criteria.isdelete', 0);
    })
    ->select('criteria.*')
    ->get();

$arr_criteria_group = [];

foreach ($criteria_group as $key => $value) {
    $arr_criteria_group[$key] = $value;
}

if(!empty($criterias)){
    foreach ($criterias as $key => $value) {
        $arr_criteria_group['criterias'][$key] = $criterias[$key];
    }
} else {
    $arr_criteria_group['criterias'] = '';
}

$one = $arr_criteria_group;
