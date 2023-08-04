<?php
use Illuminate\Database\Capsule\Manager as DB;
$one =  DB::table('meta')->where('id', $id)->first();
if($one)
{
    $results = json_decode($one->content);
}