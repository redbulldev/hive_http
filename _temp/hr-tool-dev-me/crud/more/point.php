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