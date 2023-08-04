<?php

use Illuminate\Database\Capsule\Manager as DB;

$request = DB::table($name)->where('id', $id)->first();

$criterias =
    DB::table('request')
    ->where(function ($query) use ($id) {
        $query->where('request.id', $id);
        $query->where('request.isdelete', 0);
    })
    ->leftJoin('criteria_request', 'criteria_request.id_request', '=', 'request.id')
    ->leftJoin('criteria', 'criteria.id', '=', 'criteria_request.id_criteria')
    ->where(function ($query) use ($id) {
        $query->where('criteria.isdelete', 0);
    })
    ->select('criteria.*', 'criteria_request.range')
    ->get();

$arr_request = [];

foreach ($request as $key => $value) {
    $arr_request[$key] = $value;
}

foreach ($criterias as $key => $value) {
    $arr_request['criterias'][$key] = $criterias[$key];
}

if (empty($arr_request['criterias'])) {
    $arr_request['criterias'] = null;
} else {
    $id_criteria = $arr_request['criterias'][0]->id;

    $criteria_group =
        DB::table('criteria_has_group')
        ->where(function ($query) use ($id_criteria) {
            $query->where('id_criteria', $id_criteria);
        })->join('criteria_group', function ($join) {
            $join->on('criteria_group.id', '=', 'criteria_has_group.id_criteria_group')
                ->where(function ($query) {
                    $query->where('status', 1);
                    $query->where('isdelete', 0);
                });
        })
        ->select([
            'criteria_group.id',
            'criteria_group.name'
        ])
        ->first();

    if (!empty($criteria_group)) {
        $arr_request['criteria_group'] = $criteria_group->name;

        $arr_request['id_criteria_group'] = $criteria_group->id;
    } else {
        $arr_request['criteria_group'] = null;

        $arr_request['id_criteria_group'] = null;
    }
}

$one = $arr_request;
