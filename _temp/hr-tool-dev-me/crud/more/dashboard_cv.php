<?php
use Illuminate\Database\Capsule\Manager as DB;


// lọc data in table request //
$obj->whereIn('request.status', [2,4])->where('request.isdelete',0);

// die($params['department_id']);
//start - chức năng "Bộ lọc thông tin:"
if(!empty($params['department_id']))
{
    $idDepartment = explode('-',$params['department_id']);

    $obj->where(function($query) use ($idDepartment){
        foreach ($idDepartment as $id) {
            $query->orWhere('request.position_id' , 'LIKE', "%$id%");
        }
    });
}

// $data =[];
// die($params['assignee_id']);
if(!empty($params['assignee_id']))
{
    $idAssignee = explode('-',$params['assignee_id']);

    $obj->where(function($query) use ($idAssignee){
        foreach ($idAssignee as $key  => $id) {
            $query->orWhere('request.assignee_id' , 'LIKE', "%$id%");
        }
    });
} 
// // print_r($data);
// die();
if (!empty($params['from']) && !empty($params['to'])) {
    $from = $params['from'];
    $to = $params['to'];
    $obj->where('date', '>=', $from)->where('date', '<=', $to);
}

// // map -> position_id
// die($params['position_id']);
// get ra những bản ghi có position_id trùng với id trong bảng positions
$obj->join('positions', function ($join) {
    $join->on('positions.id', '=', 'request.position_id');
    $join->where(['positions.status'=>1, 'positions.isdelete'=>0]);
});

// map -> parent_id
// get ra những bản ghi có id trùng với parent_id trong bảng positions
$obj->join('positions as parent', function ($join) {
    $join->on('parent.id', '=', 'positions.parent_id');
    $join->where(['parent.status' => 1, 'parent.isdelete' => 0]);
});


$obj->join('cv', function ($join) {
    $join->on('cv.position_id', '=', 'positions.id');
    $join->where(['cv.isdelete'=>0])->where('cv.step', '>', 0);
}); 

// $obj->join('level_positions as lp', function ($join) {
//     $join->on('lp.position_id', '=', 'positions.id');
//     $join->where(['cv.isdelete'=>0]);
// }); 

// $obj->join('level', function ($join) {
//     $join->on('lp.level_id', '=', 'level.id');
//     $join->where(['cv.isdelete'=>0]);
// }); 

// thêm column vào struct data[]
$moreselect= ['positions.title as positions_title', 'parent.title as department_title', 'cv.step as step'];

// die($response->withJson($obj->get()));