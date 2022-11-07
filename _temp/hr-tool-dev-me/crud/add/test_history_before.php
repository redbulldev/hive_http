<?php
use Illuminate\Database\Capsule\Manager as DB;



$data = [
    'title' => 'test1',
    'status' => 1
];

DB::table('test_history')->insert($data);

