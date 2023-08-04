<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($data->username) && !empty($data->date)) {
    $allhis = DB::table('history')->where(['username' => $data->username])->limit(5)->orderBy('date', 'DESC')->get()->toArray();
    $historyData = ['history' => json_encode($allhis), 'lastdate' => $data->date];
    DB::table('users')->where(['username' => $data->username])->update($historyData);
}
