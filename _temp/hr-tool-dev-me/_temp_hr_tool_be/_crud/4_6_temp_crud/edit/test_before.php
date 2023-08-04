<?php

use Illuminate\Database\Capsule\Manager as DB;


$status = DB::table('test')->where('id', 1)->update(['title'=>"xxx11"]);

// $status['title'];
// echo $status['id'];
print_r($status);

die('ok');