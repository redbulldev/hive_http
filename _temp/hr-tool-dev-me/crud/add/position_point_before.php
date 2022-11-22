<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

$name='level_positions';
// $name='positions';

// echo 'ok';

// throwError($container, $request, [
//     'point' => v::length(1, 6)->notEmpty()->noWhitespace()
// ]);

if(!empty($data->point)){
    $data->point = trim($data->point);
}

if (!empty($data->point)) {
    if (!preg_match("^(\d)*(\.)?([0-9]{1})?$", $data->point, $matches)) {
    	print_r($matches);
        throw new Exception('Enter only numbers and periods (.)');
    }
}

print_r($data->point);die('ok');



// update point in table level_positions - các trường hợp sảy ra
// 1. point đã tồn tại -> update theo id table level_positions
// 2. point không tồn tại và thay đổi default(1) thành giá trị khác -> thêm mới point theo level_id và position_id tương ứng
// 3. point không tồn tại default(1) và giũa nguyên giá trị -> thêm mới point theo level_id và position_id tương ứng
 
// 
// 1. point đã tồn tại -> update theo id table level_positions
// $check_point =  DB::table('level_positions as lp')->where('id', $id)->where('isdelete', 0)->first();

if (!empty($data->arrayid)) {
    foreach($arrayid as $key =>$value){
        $update_level_position = DB::table('level_positions')->where('id', $value)->update([
            'point' => $data->point
        ]);
    }

    // đề xuất:  $data->point
    // $data_point_exist = ['level'=>[],'position'=>[],'point'=>[]]
}

// 2. point không tồn tại và thay đổi default(1) thành giá trị khác -> thêm mới point theo level_id và position_id tương ứng
if (empty($check_point->id)) {
    // $level_positions = DB::table('level_positions as lp')->where(['level_id' => $data->level_id, 'position_id' => $data->position_id])->update([
    //     'point' => $data->point
    // ]);

    foreach($data->levelids as $key =>$levelid){
        foreach($data->positionids as $index =>$positionid){

            $check = DB::table('level_positions')->where(['level_id' => $levelid, 'position_id' => $positionid])->first();

            if (!empty($check)) {
                $create_level_position = DB::table('level_positions')->insert([
                    'level_id' => $data->level_id,
                    'position_id' => $data->position_id,
                    'point' => $data->point
                ]);
            }
        }

    }
   // đề xuất:  $data->point
    // $data_point_not_exist = ['level'=>[],'position'=>[],'point'=>[]]
}


















// Giá trị mặc định bằng 1
// Hệ thống hiển thị input box để chỉnh sửa
// - required
// - maxlength=6
// - Chỉ nhập chữ số và dấu chấm (.)
// - Tối đa 4 chữ số sau dấu chấm
// - Tự cắt số 0 đầu/ cuối(sau dấu chấm) sau khi lưu
// - trim() khoảng trắng
// Nút xác nhận chỉ Lưu khi có chỉnh sửa