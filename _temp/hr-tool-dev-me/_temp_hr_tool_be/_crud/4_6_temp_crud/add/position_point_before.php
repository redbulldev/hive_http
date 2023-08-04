<?php

use Illuminate\Database\Capsule\Manager as DB;
use Respect\Validation\Validator as v;

$name = 'level_positions';

$exception_feature = true;

$data_validates = json_decode($request->getBody());

foreach ($data_validates as $key => $title) {
    foreach ($title as $index => $point) {
        if (is_string($point)) {
            throw new Exception('Data must be numeric!');
        }

        if (is_int($point)  || is_float($point)) {
            $point = rtrim($point, 0);

            $numlength = strlen((string) $point);

            if ($numlength < 0 || $numlength > 6) {
                throw new Exception('Invalid data, length number > 0 && <= 6!');
            }
        }

        if (!empty($point)) {
            $point = (string) $point;

            $point = ltrim($point, 0);

            $count = strlen($point);

            $dot = strpos($point, '.');

            $dots = substr_count($point, '.');

            if (($count != 1 && $count == $dot + 1) || $dots > 1 || $count > 6) {
                throw new Exception('Invalid data!');
            }
        }
    }
}

// save level_position //
$get_levels = DB::table('level')->where(['status' => 1, 'isdelete' => 0])->get();

$levels = [];

function getObjPosition()
{
    return DB::table('positions')->leftJoin('positions as parent', 'parent.id', '=', 'positions.parent_id')
        ->where(['positions.status' => 1, 'positions.point_status' => 1, 'positions.isdelete' => 0, 'parent.isdelete' => 0])
        ->select('parent.title AS parent_title', 'positions.title AS title', 'positions.id AS id')->get();
}

function checkPosition($position)
{
    $check_positions = getObjPosition();

    foreach ($check_positions as $key => $value) {
        if ($value->title == $position) {
            return $value->id;
        }
    }

    return false;
}

function getPosition($position)
{
    $get_positions = getObjPosition();

    foreach ($get_positions as $value) {
        if ($value->title == $position) {
            return $value->title;
        }
    }

    return false;
}

function checkLevel($value)
{
    $check = DB::table('level')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($check)) {
        return $check->id;
    }

    return false;
}

function getLevel($value)
{
    $level = DB::table('level')->where('title', $value)->where(['status' => 1, 'isdelete' => 0])->first();

    if (!empty($level)) {
        return $level->title;
    }

    return false;
}

foreach ($get_levels as $index => $v) {
    $levels[$v->id] = $v->title;
}

$data_point_positions = json_decode($request->getBody(), true);

foreach ($data_point_positions as $key => $title) {
    if ($key == getPosition($key)) {
        $position_id = checkPosition($key);

        foreach ($title as $index => $lable) {
            if ($index == getLevel($index) && $key == getPosition($key)) {
                DB::table('level_positions')
                    ->whereIn('level_id', [checkLevel($index)])->whereIn('position_id', [checkPosition($key)])
                    ->update([
                        'point' => $data_point_positions[$key][$index]
                    ]);
            }
        }
    }
}
