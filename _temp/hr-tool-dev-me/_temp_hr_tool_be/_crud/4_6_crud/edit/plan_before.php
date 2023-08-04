<?php

use Illuminate\Database\Capsule\Manager as DB;

$name = 'request';

if (isset($data->month) || isset($data->year)) {
    $old = DB::table($name)->where('id', $id)->first();
    if ($old) {
        $current = strtotime($data->year . '-' . $data->month . '-' . (isset($data->day) ? $data->day : ($old->day > 0 ? $old->day : 30)) . ' 23:59:59');
        $data->date = date('Y-m-d', $current);
    }
}
