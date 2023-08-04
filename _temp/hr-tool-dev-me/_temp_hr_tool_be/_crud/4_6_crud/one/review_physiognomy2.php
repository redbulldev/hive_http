<?php
use Illuminate\Database\Capsule\Manager as DB;
$one =  DB::table('review_physiognomy')->where(['cv_id'=> $id,'issecond'=>1])->first();