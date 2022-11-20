<?php

use Illuminate\Database\Capsule\Manager as DB;

// if (isset($data->requestor) && is_array($data->requestor) && count($data->requestor) > 0) {
//     // echo $id; print_r($id); die('test');
//     foreach ($data->requestor as $user_id) {
//         $pradd = ['user_id' => $user_id, 'position_id' => $data->id, 'status'=> 0];
//         DB::table('positions_requester')->insert($pradd);
//         echo 'ok';
//         if (isset($data->parent_id)) {
//             $pradd2 = ['user_id' => $user_id, 'position_id' => $data->parent_id, 'status'=> 0];
//             $check =  DB::table('positions_requester')->where($pradd2)->first();
//             if(!$check)DB::table('positions_requester')->insert($pradd2);
//         }
//     }
// }


// if (isset($data->levels) && is_array($data->levels) && count($data->levels) > 0) {
//     foreach ($data->levels as $level_id) {
//         $pradd = ['level_id' => $level_id, 'position_id' => $data->id, 'point'=>1];
//         DB::table('level_positions')->insert($pradd);
//         echo 'ok1';

//         // if (isset($data->parent_id)) {
//         //     $pradd2 = ['level_id' => $level_id, 'position_id' => $data->parent_id, 'point'=>1];
//         //     $check =  DB::table('level_positions')->where($pradd2)->first();
//         //     if(!$check)DB::table('level_positions')->insert($pradd2);
//         // }
//     }
// }


if (isset($data->see_cvs) && is_array($data->see_cvs) && count($data->see_cvs) > 0) {
    foreach ($data->see_cvs as $user_id) {
        $pradd = ['user_id' => $user_id, 'position_id' => $id, 'status'=>1];
        DB::table('positions_requester')->insert($pradd);
        // echo 'ok2';

         if (isset($data->parent_id)) {
            $pradd2 = ['user_id' => $user_id, 'position_id' => $data->parent_id, 'status'=> 1];
            $check =  DB::table('positions_requester')->where($pradd2)->first();
            if(!$check)DB::table('positions_requester')->insert($pradd2);
        }
    }
}
// die('test1');
