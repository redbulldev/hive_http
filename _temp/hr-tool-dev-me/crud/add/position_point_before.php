<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

$name = 'level_positions';

$exception_feature = true;

// $exception_feature = true;

// // echo 'ok';

// // throwError($container, $request, [
// //     'point' => v::length(1, 6)->notEmpty()->noWhitespace()
// // ]);

// if(!empty($data->point)){
//     $data->point = trim($data->point);
// }

// if (!empty($data->point)) {
//     if (!preg_match("^(\d)*(\.)?([0-9]{1})?$", $data->point, $matches)) {
//     	print_r($matches);
//         throw new Exception('Enter only numbers and periods (.)');
//     }
// }

// print_r($data->point);die('ok');



// // update point in table level_positions - các trường hợp sảy ra
// // 1. point đã tồn tại -> update theo id table level_positions
// // 2. point không tồn tại và thay đổi default(1) thành giá trị khác -> thêm mới point theo level_id và position_id tương ứng
// // 3. point không tồn tại default(1) và giũa nguyên giá trị -> thêm mới point theo level_id và position_id tương ứng
 
// // 
// // 1. point đã tồn tại -> update theo id table level_positions
// // $check_point =  DB::table('level_positions as lp')->where('id', $id)->where('isdelete', 0)->first();

// if (!empty($data->arrayid)) {
//     foreach($arrayid as $key =>$value){
//         $update_level_position = DB::table('level_positions')->where('id', $value)->update([
//             'point' => $data->point
//         ]);
//     }

//     // đề xuất:  $data->point
//     // $data_point_exist = ['level'=>[],'position'=>[],'point'=>[]]
// }

// // 2. point không tồn tại và thay đổi default(1) thành giá trị khác -> thêm mới point theo level_id và position_id tương ứng
// if (empty($check_point->id)) {
//     // $level_positions = DB::table('level_positions as lp')->where(['level_id' => $data->level_id, 'position_id' => $data->position_id])->update([
//     //     'point' => $data->point
//     // ]);

//     foreach($data->levelids as $key =>$levelid){
//         foreach($data->positionids as $index =>$positionid){

//             $check = DB::table('level_positions')->where(['level_id' => $levelid, 'position_id' => $positionid])->first();

//             if (!empty($check)) {
//                 $create_level_position = DB::table('level_positions')->insert([
//                     'level_id' => $data->level_id,
//                     'position_id' => $data->position_id,
//                     'point' => $data->point
//                 ]);
//             }
//         }

//     }
//    // đề xuất:  $data->point
//     // $data_point_not_exist = ['level'=>[],'position'=>[],'point'=>[]]
// }





// Giá trị mặc định bằng 1
// Hệ thống hiển thị input box để chỉnh sửa
// - required
// - maxlength=6
// - Chỉ nhập chữ số và dấu chấm (.)
// - Tối đa 4 chữ số sau dấu chấm
// - Tự cắt số 0 đầu/ cuối(sau dấu chấm) sau khi lưu
// - trim() khoảng trắng
// Nút xác nhận chỉ Lưu khi có chỉnh sửa




//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (is_string($data->point)) {
    throw new Exception('Data must be numeric!');
}

if (is_int($data->point)) {
    if ($data->point < 1) {
        throw new Exception('Do not enter leading zeros!!');
    }

    throwError($container, $request, [
        'point' => v::digit()->length(1, 6)->notEmpty()->noWhitespace(),
    ]);
}

if (is_float($data->point)) {
    $numlength = strlen((string) $data->point);

    if ($numlength < 0 || $numlength > 6) {
        throw new Exception('Invalid data, length number > 0 && <= 6!');
    }

    throwError($container, $request, [
        'point' => v::floatVal()->notEmpty()->noWhitespace(),
    ]);
}
// die('die');

$data->point = (string) $data->point;

if (!empty($data->point)) {
    $data->point = ltrim($data->point, 0);

    $count = strlen($data->point);

    $dot = strpos($data->point, '.');
    // echo $dot;die();

    $dots = substr_count($data->point, '.');

    if ($count != 1 && $count == $dot + 1 || $dot === 0  || $dots > 1) { //
        throw new Exception('Invalid data!');
    }

    if ($dot) {
        $data->point = rtrim($data->point, 0);

        $count = strlen($data->point);

        $dot = strpos($data->point, '.');

        if ($count == $dot + 1) {
            $data->point = rtrim($data->point, '.');
        }
    }

    // echo $dot;die();
}

// die('ok');















$get_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$level_positions = DB::table('level_positions as lp')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

$levels = [];

function checkPosition($value)
{
    $check = DB::table('positions')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($check)) {
        return $check->id;
    }

    return false;
}

function getPosition($value)
{
    $position = DB::table('positions')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->where('parent_id', '!=', 0)->first();

    if (!empty($position)) {
        return $position->title;
    }

    return false;
}

function checkLevel($value)
{
    $check = DB::table('level')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($check)) {
        return $check->id;
    }

    return false;
}

function getLevel($value)
{
    $level = DB::table('level')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($level)) {
        return $level->title;
    }

    return false;
}

function checkLevelPosition($value)
{
    $check = DB::table('level_positions')->where('position_id', $value)->where(['isdelete' => 0])->where('position_id', '!=', 0)->first();

    if (!empty($check)) {
        return $check->position_id;
    }

    return false;
}

foreach ($get_levels as $index => $v) {
    $levels[$v->id] = $v->title;
}







// $datas = json_decode($request->getBody());

// foreach ($datas as $item => $data) {
//     // print_r($item); //status data end
//     foreach ($data as $key => $title) {
//         // print_r($key); //test 7x
//         foreach ($title as $index => $lable) {
//             print_r($lable); //2 1 1 1..
//         } 
//     }  
// }         

// die($response->withJson($datas['data']));
// die('end');


// // *** update - quy đổi điểm
// // 1. check position
// // 2. check level
// // 3. -> true ===> inserOrUpdate
// // note: $data_point_positions là data trả về từ frontend
// $data_point_positions = json_decode($request->getBody());
$data_point_positions = json_decode($request->getBody(), true);
// die($data_point_positions);

// $file = 'filename.txt';
// // $s= substr($file, 0, strpos($file, '.')); //filename
// $s = substr($file, strpos($file, '.')+1); //txt
// die($s);

foreach($data_point_positions as $key => $title)
{
            // print_r($key);die();
      // print_r(getPosition($key)); die();
     // echo getPosition($key); die();
    if($key == getPosition($key)) {
        // print_r($key);

        $position_id = checkPosition($key);

        foreach($title as $index => $lable) {
            $format_level = substr($index, strpos($index, '.')+1);

            if($format_level == getLevel($format_level) && $key == getPosition($key)){
                // print_r($data_point_positions[$key][$index]);die();

                // $status = DB::table('level_positions')->where('level_id', checkLevel($index))->where('position_id', checkPosition($key))->update([
                $status = DB::table('level_positions')->where('id', substr($index, 0, strpos($index, '.')))->update([
                    'point' => $data_point_positions[$key][$index]
                ]);

                // if (!empty($status)) {
                //     echo $status;
                // }else{
                //     echo $status;
                // }
            } 
            
            // $insert = DB::table('level_positions')->where('id', checkLevel($index))->where('id', checkPosition($key))->first();
//             print_r($insert);

            $level_not_exist = substr($index, 0, strpos($index, '.')); //filename
                // echo $level_not_exist.';';

            if ($level_not_exist == 0) {
                // echo $level_not_exist.';';

                $level_id = checkLevel($format_level);

                DB::table('level_positions')->insert([
                    'level_id' => $level_id,
                    'position_id' => $position_id,
                    'point' => $data_point_positions[$key][$index]
                ]);
            }




            
            // if (empty($insert)) {
            //     $level_id = checkLevel($index);

            //     DB::table('level_positions')->insert([
            //         'level_id' => $level_id,
            //         'position_id' => $position_id,
            //         'point' => $data_point_positions[$key][$index][$lable]
            //     ]);
            // }
        }
    } 
        // else {
    //     if (!empty(checkPosition($key))) {
    //         $position_id = checkPosition($key);

    //         foreach($title as $index => $lable) {
    //             DB::table('level_positions')->insert([
    //                 'level_id' => $index,
    //                 'position_id' => $position_id,
    //                 'point' => $data_point_positions[$title][$index][$lable]
    //             ]);
    //         }

    //         // foreach($levels as $index => $level){
                
    //         // }
    //     }
    // }
}

// print_r($test);



die('end');







