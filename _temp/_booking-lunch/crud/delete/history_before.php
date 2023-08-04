<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($id)) {
    $history = DB::table('history')->where(['id' => $id])->first();
    if($history)
    {
        $allhis = DB::table('history')->where(['username' => $history->username])->limit(5)->orderBy('date', 'DESC')->get()->toArray();
        $historyData = ['history' => json_encode($allhis)];
        DB::table('users')->where(['username' => $history->username])->update($historyData);
    }
}
