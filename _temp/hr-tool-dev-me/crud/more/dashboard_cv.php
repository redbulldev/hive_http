<?php
use Illuminate\Database\Capsule\Manager as DB;


// lọc data in table request //

// die($params['level_id']);
$obj->whereIn('request.status', [2, 4])->where('request.isdelete',0);

// die($params['level_id']);
//start - chức năng "Bộ lọc thông tin:"
if(!empty($params['department_id']))
{
    $idrole= explode('-',$params['department_id']);
    // print_r($idrole);
   
    $obj->where(function($query) use ($idrole){
        foreach ($idrole as $id) {
            $query->orWhere('request.position_id' , 'LIKE', "%$id%");
        }
    });
}


// if (!empty($params['from']) && !empty($params['to'])) {
//     $from = $params['from'];
//     $to = $params['to'];
//     $obj->where('date', '>=', $from)->where('date', '<=', $to);
// }

// // map -> position_id
// // die($params['position_id']);
// $obj->join('positions', function ($join) {
//     $join->on('positions.id', '=', 'request.position_id');
//     $join->where(['positions.status'=>1, 'positions.isdelete'=>0]);
// });

// // map -> parent_id
// // $obj->join('positions as parent', function ($join) {
// //     $join->on('parent.id', '=', 'positions.parent_id');
// //     $join->where(['parent.status' => 1, 'parent.isdelete' => 0]);
// // });

// // thêm column vào struct data[]
// $moreselect= ['positions.title as positions_title', 'parent.title as department_title'];

// print_r();
 die($response->withJson($obj->get()));