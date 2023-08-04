<?php

use Illuminate\Database\Capsule\Manager as DB;

error_reporting(E_ALL ^ E_NOTICE);

$get_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$level_positions = DB::table('level_positions as lp')->where(['isdelete' => 0])->where('position_id', '!=', 0)->get();

$lables = [];

$levels = [];

function getObjPosition()
{
    return DB::table('positions')->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')
        ->where(['positions.status' => 1, 'positions.point_status' => 1, 'positions.isdelete' => 0, 'parent.isdelete' => 0])
        ->select('parent.title AS parent_title', 'positions.title AS title', 'positions.id AS id')->get();
}

$get_positions = getObjPosition();

function checkPosition($position)
{
    $check_positions = getObjPosition();

    foreach ($check_positions as $key => $value) {
        if ($value->id == $position) {
            return $value->id;
        }
    }

    return false;
}

function getPosition($position)
{
    $get_positions = getObjPosition();

    foreach ($get_positions as $value) {
        if ($value->id == $position) {
            return $value->title;
        }
    }

    return false;
}

function checkLevel($value)
{
    $check = DB::table('level')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($check)) {
        return $check->id;
    }

    return false;
}

function getLevel($value)
{
    $level = DB::table('level')->where('id', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($level)) {
        return $level->title;
    }

    return false;
}

foreach ($get_positions as $key => $value) {
    $lables[$value->id] = $value->title;
}

foreach ($get_levels as $index => $v) {
    $levels[$v->id] = $v->title;
}

$point_positions = [[[]]];

for ($i = 0; $i < count($level_positions); $i++) {
    if ($level_positions[$i]->level_id == checkLevel($level_positions[$i]->level_id) && $level_positions[$i]->position_id == checkPosition($level_positions[$i]->position_id)) {
        $point_positions[getPosition($level_positions[$i]->position_id)][getLevel($level_positions[$i]->level_id)] = $level_positions[$i]->point;
    }
}

foreach ($lables as $key => $lable) {
    if (empty($point_positions[$lable])) {
        $point_positions[$lable] = null;
    }
}

unset($point_positions[0]);

$results = [
    'status' => 'success',
    'data' => $point_positions ? $point_positions : null,
    'total' => $point_positions ? count($point_positions) : null,
    'time' => time(),
];
