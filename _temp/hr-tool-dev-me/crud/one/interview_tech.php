<?php
use Illuminate\Database\Capsule\Manager as DB;
$one =  DB::table($name)->where('cv_id', $id)->first();