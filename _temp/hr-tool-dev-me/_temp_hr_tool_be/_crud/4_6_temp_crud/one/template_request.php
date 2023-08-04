<?php

use Illuminate\Database\Capsule\Manager as DB;

function getTable($name, $select)
{
    $status = DB::table($name)
        ->select($select)
        ->where('isdelete', 0)
        ->get();

    if ($status) {
        return $status;
    }

    return null;
}

function getTableAndWhere($name, $where, $id, $more)
{
    $status = '';

    if ($more === 'first') {
        $status = DB::table($name)
            ->where($where, $id)
            ->where('isdelete', 0)
            ->first();
    }

    if ($more === 'get') {
        $status = DB::table($name)
            ->where($where, $id)
            ->get();
    }

    if ($status) {
        return $status;
    }

    return null;
}

$template_request = getTableAndWhere('template_request', 'id', $id, 'first');

if (!empty($template_request)) {
    $template_criteria = getTableAndWhere('template_criteria', 'id_template', $template_request->id, 'get');
}

if (!empty($template_request) && count($template_criteria)) {
    $template_criteria = getTableAndWhere('template_criteria', 'id_template', $template_request->id, 'get');

    $criteria = getTable('criteria', ['*']);

    $criteria_elements = getTable('criteria_elements', ['*']);

    $arr_template_request = [];

    $arr_template_criteria = [];

    $arr_res = [];

    array_push($arr_template_request, $template_request);

    array_push($arr_res, $template_request);

    foreach ($template_criteria as $key => $i) {
        if ($arr_template_request[0]->id === $i->id_template) {
            foreach ($criteria as $key => $j) {
                if ($i->id_criteria === $j->id) {
                    $arr_template_request['templates'][$key] = $criteria[$key];
                }
            }
        }
    }

    foreach ($arr_template_request as $key => $item) {
        foreach ($item as $index => $value) {
            foreach ($criteria_elements as $index => $l) {
                if (!empty($value->id) && $l->id_criteria === $value->id) {
                    $arr_template_request['templates']['elements'][$index] = $criteria_elements[$index];
                }
            }
        }
    }

    $count = 0;

    $arr_criteria = [];

    $arr_criteria_elements = [];

    foreach ($arr_template_request['templates'] as $index => $value) {
        if (!empty($value->id) && is_int($value->id)) {
            $arr_criteria[$count] = $value;

            $count++;
        }
    }

    $count = 0;

    foreach ($arr_template_request['templates']['elements'] as $index => $value) {
        $arr_criteria_elements[$count] = $value;

        $count++;
    }

    for ($i = 0; $i < count($arr_criteria); $i++) {
        for ($j = 0; $j < count($arr_criteria_elements); $j++) {
            if ($arr_criteria[$i]->id ===  $arr_criteria_elements[$j]->id_criteria) {
                $arr_criteria[$i]->{$j} = $arr_criteria_elements[$j];
            }
        }
    }

    $arr_res['criteria'] = $arr_criteria;

    $one = $arr_res;
} else {
    $one = getTableAndWhere('template_request', 'id', $id, 'first');
}
