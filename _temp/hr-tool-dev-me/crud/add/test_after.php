<?php
use Illuminate\Database\Capsule\Manager as DB;

// die('ok');
$data = [
    'title' => 'test123',
    'des' => 'test',
    'status' => 0
];

DB::table('test')->insert($data);

