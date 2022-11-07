<?php

use Illuminate\Database\Capsule\Manager as DB;

if (isset($data->levels) && is_array($data->levels) && count($data->levels) > 0) {
    DB::table('request_level')->where('request_id',$id)->delete();
    foreach ($data->levels as $level) {
        $pradd = ['level_id' => $level->id, 'request_id' => $id];
        DB::table('request_level')->insert($pradd);
    }
}


