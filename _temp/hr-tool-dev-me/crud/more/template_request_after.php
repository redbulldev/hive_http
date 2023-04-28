<?php

use Illuminate\Database\Capsule\Manager as DB;

$template_request = DB::table('template_request')
    ->leftJoin('criteria_group', 'criteria_group.id', '=', 'template_request.id_criteria_group')
    ->select(['template_request.*', 'criteria_group.name AS name_criteria_group'])
    ->get();

$results = [
    'status' => 'success',
    'data' => !empty($template_request) ? $template_request : null,
    'total' => !empty($template_request) ? count($template_request) : null,
    'time' => time(),
];
