<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj->where('cv.isdelete', 0);

$obj->leftJoin('positions', 'positions.id', '=', 'cv.position_id')
    ->leftJoin('request', 'request.id', '=', 'cv.request_id')
    ->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')
    ->leftJoin('level', 'level.id', '=', 'cv.level_id');

if (!empty($params['from']) && !empty($params['to'])) {
    $from = strtotime($params['from']);

    $to = strtotime('+1 day', strtotime($params['to']));

    if ($from > 0 && $to > 0) {
        $obj->where($name . '.datecreate', '>=', $from)->where($name . '.datecreate', '<=', $to);
    }
}

if (!empty($params['department_id'])) {
    $department_id = explode('-', $params['department_id']);

    $obj->where(function ($query) use ($department_id) {
        foreach ($department_id as $id) {
            $query->orWhere('positions.parent_id', 'LIKE', "%$id%");
        }
    });
}

$moreselect = [
    'parent.title as department_title',
    'positions.title as positions_title',
    'level.title as level_title',
    'request.interview_cv',
    'request.pass_cv',
    'request.offer_cv',
    'request.offer_success',
    'request.onboard_cv',
    'request.fail_job',
    'request.employees',
    'request.month',
    'request.year'
];
