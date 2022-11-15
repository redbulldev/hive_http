<?php

use Illuminate\Database\Capsule\Manager as DB;

$obj->where(['point_status' => 1, 'isdelete' => 0, 'status' => 1])->where('parent_id', '!=', 0);


// $levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0]);

// $results = [
//     'status' => 'success',
//     'summary' => $levels->get(),
//     // 'department' => $department,
//     'data' => $ketqua ? $ketqua->all() : null,
//     'total' => $ketqua ? $ketqua->count() : null,
//     'time' => time(),
// ];



// $obj->join('positions', function ($join) {
//     $join->on('positions.id', '=', 'request.position_id');
//     $join->where(['positions.status'=>1, 'positions.isdelete'=>0]);
// });

// $obj->join('level_positions', function ($join) {
//     $join->on('positions.id', '=', 'level_positions.position_id');
//     $join->where(['level_positions.isdelete'=>0]);
//     $join->where(['positions.isdelete'=>0]); 
// });
// // die($response->withJson($obj->get()));

// $obj->join('level', function ($join) {
//     $join->on('level.id', '=', 'level_positions.level_id');
//     $join->where(['level.isdelete'=>0]);
// });

// die($response->withJson($obj->get()));

// $getAll = $obj->join('level_positions', 'positions.id', '=', 'level_positions.position_id')->where(['level_positions.isdelete'=>0])
//             ->join('level', 'level.id', '=', 'level_positions.level_id')->where(['level.isdelete'=>0])
//             ->get(); 


// $getAll = DB::table('level_positions as lp')->join('level', 'level.id', '=', 'lp.level_id')->where(['lp.isdelete'=>0])
//             // ->join('level', 'level.id', '=', 'level_positions.level_id')->where(['level.isdelete'=>0])
//             ->get(); 
//             $dataid = [];
// foreach($getAll as $key => $value){
//     $dataid[$key] = $value->level_id;
// }           
// die($response->withJson($getAll));



//  $test = DB::table('level_positions as lp')->join('positions as p', 'p.id','=','lp.position_id')->get();
// die($test);

// die($getAll);
// ＄users = DB::table('users')
//             ->join('contacts', 'users.id', '=', 'contacts.user_id')
//             ->join('orders', 'users.id', '=', 'orders.user_id')
//             ->select('users.*', 'contacts.phone', 'orders.price')
//             ->get();


// cac buoc 
// - diem get tu level_positions
// kiem tra

