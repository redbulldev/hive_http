<?php

use Illuminate\Database\Capsule\Manager as DB;

if (!empty($params['from']) && !empty($params['to'])) {
    $one = ["title" => "Exported"];
    // Download file
    $filename = "hivetech-".date('Y-m-d').".xls"; // File Name
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel");
    $datadate=[];
    $datahis = DB::table('history')->where('date', '>=', $params['from'])->where('date', '<=', $params['to'])->get();
    foreach ($datahis as $his) {
        $datadate[$his->username . '_' . $his->date] = $his;
    }
    $users = DB::table('history')->where('date', '>=', $params['from'])->where('date', '<=', $params['to'])->select('username')->groupBy('username')->orderBy('username', 'ASC')->get();
    $dates = DB::table('history')->where('date', '>=', $params['from'])->where('date', '<=', $params['to'])->select('date')->groupBy('date')->orderBy('date', 'ASC')->get();
    // Write data to file
    $flag = false;
    foreach ($users as $row) {
        if (!$flag) {
            $head = "Fullname";
            foreach ($dates as $date) {
                $head .= "\t".$date->date;
            }
            // display field/column names as first row
            // echo implode("\t", array_keys($row)) . "\r\n";
            $head .= "\r\n";
            echo $head;
            $flag = true;
        }
        $body = $row->username;
        foreach ($dates as $date) {
            //$his = DB::table('history')->where('date',  $date->date)->where('username',  $row->username)->first();
            if(!empty($datadate[$row->username . '_' . $date->date])){
                $his = $datadate[$row->username . '_' . $date->date];
                $body .= "\t" . (($his->booked === 1 && $his->ate === 1) ? "X" : ($his->booked === 1 ? "Đ" : ($his->ate ? "Ă" : "")));
            }else $body .= "\t";
        }
        $body .= "\r\n";
        echo $body;
        //echo implode("\t", array_values($row)) . "\r\n";
    }
    die();
}
