<?php

use Illuminate\Database\Capsule\Manager as DB;

$deadline = date('Y-m-t');
echo $deadline;
$check = DB::table('request')->where(['deadline'=> $deadline,'isauto'=>1])->first();
if(!$check)
{
    $dataAdd=[
        'author_id' => 'AUTO',
        'requestor_id'=>'AUTO',
        'decision_id'=>'AUTO',
        'target'=>0,
        'priority'=>0,
        'date'=> $deadline,
        'deadline' => $deadline,
        'isauto'=>1,
        'status'=>2,
        'day'=> date('t'),
        'month' => date('m'),
        'year' => date('Y'),
        'datecreate'=>time(),
        'datemodified' => time(),
        'isdelete'=>0
    ];
    DB::table('request')->insert($dataAdd);
}
print_r($check);
die();
