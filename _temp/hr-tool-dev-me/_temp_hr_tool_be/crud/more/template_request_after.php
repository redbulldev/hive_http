<?php

use Illuminate\Database\Capsule\Manager as DB;

function getDataTable($obj, $where, $value)
{
    return $obj->leftJoin('criteria_group', 'criteria_group.id', '=', 'template_request.id_criteria_group')
        ->where('template_request.parent_id', $where, $value)
        ->select(['template_request.*', 'criteria_group.name AS name_criteria_group'])
        ->get();
}

$more = !empty($params['more']) ? $params['more'] : '';

if ($more === 'parent') {
    $template_request = getDataTable($obj, '=', 0);
} else {
    $template_request = getDataTable($obj, '>', -1);
}

$total = DB::table('template_request')
    ->where('isdelete', 0)
    ->count();

$results = [
    'status' => 'success',
    'data' => $template_request ? $template_request : null,
    'total' => $total,
    'time' => time(),
];
