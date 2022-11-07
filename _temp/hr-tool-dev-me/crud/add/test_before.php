<?php
use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

// thay đổi status trong bảng test thành 3
// $data->title = 'test';
// $data->des = 'test';
// $data->status = 0;

// $data = [
//     'title' => 'test',
//     'des' => 'test',
//     'status' => 0
// ];

// DB::table('test')->insert($data);


//////////////
// // validate - 

// throwError($container,$request, [
//     'title' => v::length(3, 200)->notEmpty()
// ]);

// if(isset($data->title))
// {
//     if(DB::table($name)->where('title',trim($data->title))->count())
//     {
//         throw new Exception('Title already exists');
//     }
// }
// // from - hr-tool-be/crud/add/source_before.php