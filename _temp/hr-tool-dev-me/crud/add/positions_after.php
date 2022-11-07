<?php

use Illuminate\Database\Capsule\Manager as DB;

if (isset($data->requestor) && is_array($data->requestor) && count($data->requestor) > 0) {
    foreach ($data->requestor as $user_id) {
        $pradd = ['user_id' => $user_id, 'position_id' => $id];
        DB::table('positions_requester')->insert($pradd);
        if (isset($data->parent_id)) {
            $pradd2 = ['user_id' => $user_id, 'position_id' => $data->parent_id];
            $check =  DB::table('positions_requester')->where($pradd2)->first();
            if(!$check)DB::table('positions_requester')->insert($pradd2);
        }
    }
}
