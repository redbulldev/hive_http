<?php

use Illuminate\Database\Capsule\Manager as DB;

// $obj->whereIn('request.status', [2, 4])->where('request.isdelete', 0);




// $getobj = DB::table('cv')->leftJoin('positions', 'positions.id', '=', 'cv.position_id')
$obj->leftJoin('positions', 'positions.id', '=', 'cv.position_id')
    ->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')
    ->leftJoin('level', 'level.id', '=', 'cv.level_id')
    ->leftJoin('source', 'source.id', '=', 'cv.source_id');


//start - chức năng "Bộ lọc thông tin:"
// die($params['from']);
if (!empty($params['from']) && !empty($params['to'])) {
    $from = strtotime($params['from']);
    $to = strtotime($params['to']);
    // $obj->where('date', '>=', $from)->where('date', '<=', $to);
    $obj->where($name . '.datecreate', '>=', $from)->where($name . '.datecreate', '<=', $to);
}

if (!empty($params['assignee_id'])) {
    $assignee_id = explode('-', $params['assignee_id']);
    // print_r($assignee_id);die();
    // die('o1k');

    $obj->where(function ($query) use ($assignee_id) {
        foreach ($assignee_id as $key  => $id) {
            $query->orWhere('cv.assignee_id', 'LIKE', "%$id%");
        }
    });
}

if (!empty($params['department_id'])) {
    $department_id = explode('-', $params['department_id']);

    $obj->where(function ($query) use ($department_id) {
        foreach ($department_id as $id) {
            $query->orWhere('cv.position_id', 'LIKE', "%$id%");
        }
    });
}
// $obj->join('positions', function ($join) {
//     $join->on('positions.id', '=', 'request.position_id');
//     $join->where(['positions.status' => 1, 'positions.isdelete' => 0]);
// });

// $obj->join('positions as parent', function ($join) {
//     $join->on('parent.id', '=', 'positions.parent_id');
//     $join->where(['parent.status' => 1, 'parent.isdelete' => 0]);
// });


// $obj->join('cv', function ($join) {
//     $join->on('cv.position_id', '=', 'positions.id');
//     $join->where(['cv.isdelete' => 0]);
// });

// $moreselect = ['positions.title as positions_title', 'parent.title as department_title', 'cv.step as step'];

// die($response->withJson($obj->get()));
die($response->withJson($obj->get()));