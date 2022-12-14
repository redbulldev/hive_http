<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj->where('cv.isdelete', 0);

// $getobj = DB::table('cv')->leftJoin('positions', 'positions.id', '=', 'cv.position_id')
// $obj->rightJoin('cv', 'cv.request_id', '=', 'request.id')
$obj->leftJoin('positions', 'positions.id', '=', 'cv.position_id')
    ->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')
    ->leftJoin('level', 'level.id', '=', 'cv.level_id')
    ->leftJoin('source', 'source.id', '=', 'cv.source_id');

    // $obj->select('cv.*');

    // $obj->selectRaw(' 
    // cv.datecreate as cv_datecreate,
    // cv.*
    // ');

//start - chức năng "Bộ lọc thông tin:"
// die($params['from']);
if (!empty($params['from']) && !empty($params['to'])) {
    // $from = $params['from'];
    // $to = $params['to'];
    // $obj->where('date', '>=', $from)->where('date', '<=', $to);

// die($params['from']);
    $from = strtotime($params['from']);
    // echo  $from .';';
    // 1669827600;1670518800; 1670571694
    // $to = strtotime($params['to']);
    $to = strtotime('+1 day', strtotime($params['to']));

    // echo  $to .';';
    // 1669827600;1670518800
    // $begin = date('Y-m-d', $from); 
    // $end = date('Y-m-d', $to); 
    // echo  $begin .';'.$end;

    if ($from > 0 && $to > 0) {
        $obj->where($name . '.datecreate', '>=', $from)->where($name . '.datecreate', '<=', $to);
        // foreach ($obj->get as $key => $value) {
        //     $begin = date('Y-m-d', $value->datecreate); 
        //     $end = date('Y-m-d', $value->datecreate); 
        // }
    }
}

// if (!empty($params['assignee_id'])) {
//     $assignee_id = explode('-', $params['assignee_id']);
//     // print_r($assignee_id);die();
//     // die('o1k');

//     $obj->where(function ($query) use ($assignee_id) {
//         foreach ($assignee_id as $key  => $id) {
//             $query->orWhere('request.assignee_id', 'LIKE', "%$id%");
//         }
//     });
// }
// die($response->withJson($obj->count()));

if (!empty($params['department_id'])) {
    $department_id = explode('-', $params['department_id']);

    $obj->where(function ($query) use ($department_id) {
        foreach ($department_id as $id) {
            $query->orWhere('positions.parent_id', 'LIKE', "%$id%");
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

$moreselect = ['positions.title as positions_title', 'parent.title as department_title', 'cv.step as step','cv.assignee_id as cv_assignee_id'];

// die($response->withJson($obj->count()));
// die($response->withJson($obj->get()));