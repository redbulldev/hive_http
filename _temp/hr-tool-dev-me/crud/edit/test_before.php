<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

die($data->title);

echo $data->title; //set id mới get được data
// echo $args['id']; //get id trên parameter
// die();






///////////////////////////
// thay đổi data trong bảng test 
// với cách này phải set data mặc định trong postman
// raw
// {
//     "id": 6,
//     "title":"Thai",
//     "des":"Thai Echomi",
//     "status":6
// }

// $data->title = 'vdsfvc';
// $data->des = 'vdfvd';
// $data->status = 0;
// die('$data->id');











///////////////////////
// update
// DB::update('UPDATE request set languages = REPLACE(languages,"' . $oldtitle . '","' . $data->title . '") WHERE languages LIKE "%' . $oldtitle . '%"');

// tương tự như cách trên khác cách thêm dữ liệu
// true
// $affected = DB::table('test')
//     ->where('id', $args['id'])
//     ->update([
//         'title' => $data->title,
//         'des' => $data->des,
//         'status' => $data->status
//     ]);












